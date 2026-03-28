import re
import sys

def convert_mysql_to_postgres(sql):
    # Remove MySQL specific SET commands and transaction commands
    sql = re.sub(r'SET SQL_MODE[^;]*;\s+', '', sql)
    sql = re.sub(r'SET time_zone[^;]*;\s+', '', sql)
    sql = re.sub(r'START TRANSACTION;\s+', '', sql, flags=re.IGNORECASE)
    sql = re.sub(r'COMMIT;\s+', '', sql, flags=re.IGNORECASE)
    sql = re.sub(r'/\*!40101[^;]*;\s+', '', sql)
    sql = re.sub(r'--.*\n', '', sql) # Remove single line comments
    
    # Replace backticks with double quotes
    sql = sql.replace('`', '"')
    
    # Add DROP TABLE statements to the beginning to ensure a clean start
    drop_tables = []
    
    # Function to replace datatypes
    def replace_datatypes(match):
        line = match.group(0)
        # Type replacements
        line = re.sub(r'\bbigint\(\d+\)\s+UNSIGNED', 'BIGINT', line, flags=re.IGNORECASE)
        line = re.sub(r'\bint\(\d+\)\s+UNSIGNED', 'INTEGER', line, flags=re.IGNORECASE)
        line = re.sub(r'\bbigint\(\d+\)', 'BIGINT', line, flags=re.IGNORECASE)
        line = re.sub(r'\bint\(\d+\)', 'INTEGER', line, flags=re.IGNORECASE)
        line = re.sub(r'\btinyint\(\d+\)\s+UNSIGNED', 'SMALLINT', line, flags=re.IGNORECASE)
        line = re.sub(r'\btinyint\(1\)', 'BOOLEAN', line, flags=re.IGNORECASE)
        line = re.sub(r'\btinyint\(\d+\)', 'SMALLINT', line, flags=re.IGNORECASE)
        line = re.sub(r'\bdouble\(\d+,\d+\)', 'DOUBLE PRECISION', line, flags=re.IGNORECASE)
        
        # Enums to text
        line = re.sub(r'enum\([^\)]+\)', 'TEXT', line, flags=re.IGNORECASE)
        line = re.sub(r'longtext[^\,]*', 'TEXT', line, flags=re.IGNORECASE)
        line = re.sub(r'mediumtext', 'TEXT', line, flags=re.IGNORECASE)
        
        # Remove UNSIGNED generally
        line = re.sub(r'\s+UNSIGNED', '', line, flags=re.IGNORECASE)
        
        # PostgreSQL doesn't use parentheses for CURRENT_TIMESTAMP default
        line = re.sub(r'current_timestamp\(\)', 'CURRENT_TIMESTAMP', line, flags=re.IGNORECASE)
        
        # Handle BOOLEAN defaults if they were converted
        if 'BOOLEAN' in line.upper():
            line = re.sub(r'\bDEFAULT\s+0\b', 'DEFAULT FALSE', line, flags=re.IGNORECASE)
            line = re.sub(r'\bDEFAULT\s+1\b', 'DEFAULT TRUE', line, flags=re.IGNORECASE)
        
        return line

    # First pass: find all AUTO_INCREMENT columns and their current values
    table_auto_increments = {}
    # Search the entire SQL for ALTER TABLE ... MODIFY ... AUTO_INCREMENT
    auto_inc_pattern = r'ALTER TABLE\s+["`]?([^"`\s;]+)["`]?\s+MODIFY\s+["`]?([^"`\s;]+)["`]?.*?AUTO_INCREMENT\s*=\s*(\d+)'
    for m in re.finditer(auto_inc_pattern, sql, re.IGNORECASE | re.DOTALL):
        table, col, val = m.groups()
        table_auto_increments[table] = {'column': col, 'value': val}

    # Second pass: Block by block processing
    blocks = sql.split(';')
    new_blocks = []
    
    for block in blocks:
        block = block.strip()
        if not block:
            continue
            
        if block.upper().startswith('CREATE TABLE'):
            # Extract table name
            m = re.search(r'CREATE TABLE\s+["`]?([^"`\s\(]+)["`]?', block, re.IGNORECASE)
            table_name = m.group(1) if m else None
            
            # Replace datatypes and defaults line by line
            lines = block.split('\n')
            new_table_lines = []
            for line in lines:
                # Apply general datatype replacements
                line = replace_datatypes(re.match(r'.*', line))
                
                # Check if this column should be a SERIAL/BIGSERIAL
                if table_name and table_name in table_auto_increments:
                    col_info = table_auto_increments[table_name]
                    col_name = col_info['column']
                    # Look for the column definition: "col_name" TYPE ...
                    if f'"{col_name}"' in line:
                        # Replace the type with its SERIAL equivalent
                        line = re.sub(r'\bBIGINT\b', 'BIGSERIAL', line, flags=re.IGNORECASE)
                        line = re.sub(r'\bINTEGER\b', 'SERIAL', line, flags=re.IGNORECASE)
                        # Also handle case where it might just say int
                        line = re.sub(r'\bINT\b', 'SERIAL', line, flags=re.IGNORECASE)

                # Fix specific corruption for age columns which might have been hit by boolean replacement
                if table_name == 'lab_test_parameters_master':
                    line = re.sub(r'DEFAULT FALSE', 'DEFAULT 0', line)
                    line = re.sub(r'DEFAULT TRUE20', 'DEFAULT 120', line)
                new_table_lines.append(line)
            block = '\n'.join(new_table_lines)
            
            # Remove Table options and replace with just )
            block = re.sub(r'\)[^\)]+ENGINE=[^\)]+$', ')', block, flags=re.IGNORECASE|re.DOTALL)
            block = re.sub(r'\)\s*ENGINE=.*', ')', block, flags=re.IGNORECASE|re.DOTALL)
            
            if table_name:
                new_blocks.append(f'DROP TABLE IF EXISTS "{table_name}" CASCADE;')
            new_blocks.append(block + ';')
            
        elif block.upper().startswith('ALTER TABLE'):
            if 'ADD PRIMARY KEY' in block.upper() or 'ADD KEY' in block.upper() or 'ADD UNIQUE KEY' in block.upper() or 'ADD CONSTRAINT' in block.upper():
                # Fix keys
                lines = block.split('\n')
                new_alter_lines = []
                for l in lines:
                    if 'MODIFY' in l.upper() and 'AUTO_INCREMENT' in l.upper():
                        continue
                    
                    # Convert ADD KEY `name` (`col`) to CREATE INDEX
                    if 'ADD KEY' in l.upper() and 'PRIMARY KEY' not in l.upper() and 'UNIQUE KEY' not in l.upper():
                        # We should extract these out as CREATE INDEX
                        # But postgres allows ALTER TABLE ... ADD CONSTRAINT? No, not for simple indexes.
                        # Wait, the dump uses ALTER TABLE "appointments" ADD KEY "appointments_doctor_id_foreign" ("doctor_id");
                        m = re.search(r'ADD KEY\s+"([^"]+)"\s+\((.+)\)', l, re.IGNORECASE)
                        if m:
                            idx_name, cols = m.groups()
                            table_name = re.search(r'ALTER TABLE\s+"([^"]+)"', block, re.IGNORECASE).group(1)
                            new_blocks.append(f'CREATE INDEX "{idx_name}" ON "{table_name}" ({cols});')
                            continue
                    
                    if 'ADD UNIQUE KEY' in l.upper():
                        l = re.sub(r'ADD UNIQUE KEY', 'ADD CONSTRAINT', l, flags=re.IGNORECASE)
                        # Ensure it has UNIQUE(cols)
                        # MySQL: ADD UNIQUE KEY "name" ("col1", "col2")
                        # PostgreSQL: ADD CONSTRAINT "name" UNIQUE ("col1", "col2")
                        m = re.search(r'ADD CONSTRAINT\s+"([^"]+)"\s+\((.+)\)', l, re.IGNORECASE)
                        if m:
                            constraint_name, cols = m.groups()
                            l = f'  ADD CONSTRAINT "{constraint_name}" UNIQUE ({cols})'
                            
                    # Remove trailing comma if last element before changing block structure is tricky.
                    new_alter_lines.append(l)
                
                # Clean up new_alter_lines commas if needed
                cleaned = '\n'.join(new_alter_lines).strip()
                if cleaned.endswith(','):
                    cleaned = cleaned[:-1]
                    
                if 'ALTER TABLE' in cleaned and len(new_alter_lines) > 1:
                    # check if the body has anything left
                    body = cleaned.split('ALTER TABLE')[1]
                    if 'ADD' in body:
                        # Fix trailing commas inside ADD
                        cleaned = re.sub(r',\s*$', '', cleaned)
                        new_blocks.append(cleaned + ';')
            elif 'MODIFY' in block.upper() and 'AUTO_INCREMENT' in block.upper():
                # Skip individual auto_increment blocks, we handle them at the end now
                pass
            else:
                new_blocks.append(block + ';')
        elif block.upper().startswith('INSERT INTO'):
            # Specific fixes for boolean columns in INSERT statements
            if '"lab_tests"' in block:
                # is_critical (column 16) and is_reviewed (column 17)
                # Structure: (id, ..., status, is_critical, is_reviewed, deleted_at, ...)
                # status is column 15.
                block = re.sub(r"('pending'|'completed'),\s*([01]),\s*([01])", 
                               lambda m: f"{m.group(1)}, {'TRUE' if m.group(2)=='1' else 'FALSE'}, {'TRUE' if m.group(3)=='1' else 'FALSE'}", 
                               block)
            if '"lab_test_parameters_master"' in block:
                # is_required is column 12.
                # Structure: (..., 'unit', is_required, created_at, updated_at)
                # Match: 'unit_string', 0/1, 'timestamp'
                block = re.sub(r"('[^']*'|NULL),\s*([01]),\s*('[^']*'|NULL),\s*('[^']*'|NULL)", 
                               lambda m: f"{m.group(1)}, {'TRUE' if m.group(2)=='1' else 'FALSE'}, {m.group(3)}, {m.group(4)}", 
                               block)
            new_blocks.append(block + ';')
        else:
            if block.strip():
                new_blocks.append(block + ';')

    # Add centralized setval calls at the end
    for table, info in table_auto_increments.items():
        col = info['column']
        val = info['value']
        new_blocks.append(f"SELECT setval('{table}_{col}_seq', {val}, false);")

    header = "START TRANSACTION;\nSET TIME ZONE 'UTC';\n\n"
    return header + '\n\n'.join(new_blocks) + '\n\nCOMMIT;'

if __name__ == '__main__':
    with open('c:/Users/nived/Downloads/patient_tracking (1).sql', 'r', encoding='utf-8') as f:
        sql = f.read()
    
    pg_sql = convert_mysql_to_postgres(sql)
    
    with open('c:/Users/nived/Downloads/patient_tracking_pg.sql', 'w', encoding='utf-8') as f:
        f.write(pg_sql)
    print("Conversion complete!")
