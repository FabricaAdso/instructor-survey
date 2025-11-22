
import pandas as pd
import mysql.connector
import os
import sys
import signal
import bcrypt
from openpyxl.styles import PatternFill

# Configurar un timeout de 10 minutos
signal.signal(signal.SIGALRM, lambda signum, frame: print("Tiempo de ejecución excedido"))
signal.alarm(600)  # 600 segundos (10 minutos)

# Configuración de la base de datos
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': 'viento3roca*',
    'database': 'instructor_survey'
}

# Función para generar hash compatible con Laravel
def generate_laravel_bcrypt(password):
    """Genera un hash bcrypt que Laravel puede verificar"""
    # Generar hash normal con bcrypt
    hash = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt(rounds=12))
    # Convertir a formato compatible con Laravel ($2y$ en lugar de $2b$)
    laravel_hash = hash.decode('utf-8').replace('$2b$', '$2y$')
    return laravel_hash

# Contraseña por defecto hasheada de forma compatible con Laravel
DEFAULT_PASSWORD = "l1d3r4r34s3n42025."
DEFAULT_HASHED_PASSWORD = generate_laravel_bcrypt(DEFAULT_PASSWORD)

def import_area_leaders(file_path):
    conn = None
    cursor = None
    failed_rows = []
    failed_indices = []

    try:
        # Verificar si el archivo existe
        if not os.path.exists(file_path):
            raise FileNotFoundError(f"El archivo {file_path} no existe")

        # Leer el archivo Excel
        leaders_df = pd.read_excel(file_path)

        # Conectar a la base de datos
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()

        print("Procesando líderes de área...")
        print(f"Usando hash bcrypt compatible con Laravel: {DEFAULT_HASHED_PASSWORD[:20]}...")

        for index, row in leaders_df.iterrows():
            try:
                # Insertar o actualizar el usuario (con is_area_leader = True)
                cursor.execute("SELECT id FROM users WHERE identity_document = %s", (row['NUMERO_DOCUMENTO'],))
                user = cursor.fetchone()

                if not user:
                    # Insertar nuevo usuario como líder de área
                    cursor.execute(
                        """INSERT INTO users
                        (identity_document, name, last_name, email, is_area_leader, password)
                        VALUES (%s, %s, %s, %s, %s, %s)""",
                        (
                            row['NUMERO_DOCUMENTO'],
                            row['NOMBRE'],
                            f"{row['PRIMER_APELLIDO']} {row['SEGUNDO_APELLIDO']}",
                            row['CORREO_ELECTRONICO'],
                            True,  # Marcamos como líder de área
                            DEFAULT_HASHED_PASSWORD  # Contraseña por defecto hasheada
                        )
                    )
                    user_id = cursor.lastrowid
                else:
                    user_id = user[0]
                    # Actualizar usuario existente como líder de área
                    cursor.execute(
                        """UPDATE users
                        SET is_area_leader = TRUE,
                            password = %s
                        WHERE id = %s""",
                        (DEFAULT_HASHED_PASSWORD, user_id)
                    )

                # Insertar o actualizar la red de conocimiento
                knowledge_network_name = row['RED_CONOCIMIENTO']
                cursor.execute("SELECT id FROM knowledge_networks WHERE name = %s", (knowledge_network_name,))
                knowledge_network = cursor.fetchone()

                if not knowledge_network:
                    cursor.execute(
                        "INSERT INTO knowledge_networks (name) VALUES (%s)",
                        (knowledge_network_name,)
                    )
                    knowledge_network_id = cursor.lastrowid
                else:
                    knowledge_network_id = knowledge_network[0]

                # Insertar en area_leaders si no existe ya la relación
                cursor.execute(
                    """SELECT id FROM area_leaders
                    WHERE user_id = %s AND knowledge_network_id = %s""",
                    (user_id, knowledge_network_id)
                )

                if not cursor.fetchone():
                    cursor.execute(
                        """INSERT INTO area_leaders
                        (user_id, knowledge_network_id)
                        VALUES (%s, %s)""",
                        (user_id, knowledge_network_id)
                    )

            except Exception as e:
                print(f"Error en la fila {index + 1}: {e}")
                failed_rows.append(row)
                failed_indices.append(index)

        conn.commit()
        print("Archivo importado correctamente")

        # Si hay filas con errores, generar un archivo Excel con estilos
        if failed_rows:
            failed_df = pd.DataFrame(failed_rows)
            failed_file_path = file_path.replace(".xlsx", "_errores.xlsx")

            with pd.ExcelWriter(failed_file_path, engine='openpyxl') as writer:
                failed_df.to_excel(writer, index=False, sheet_name='Errores')

                workbook = writer.book
                worksheet = writer.sheets['Errores']
                red_fill = PatternFill(start_color="FF0000", end_color="FF0000", fill_type="solid")

                for index in failed_indices:
                    for col in range(1, len(failed_df.columns) + 1):
                        worksheet.cell(row=index + 2, column=col).fill = red_fill

            print(f"Se generó un archivo con las filas fallidas: {failed_file_path}")

    except FileNotFoundError as e:
        print(f"Error: {e}")
    except Exception as e:
        if conn:
            conn.rollback()
        print(f"Error al importar el archivo: {e}")
        sys.exit(1)
    finally:
        if cursor:
            cursor.close()
        if conn:
            conn.close()

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Uso: python3 import_area_leaders.py <ruta_al_archivo>")
        sys.exit(1)

    file_path = sys.argv[1]
    import_area_leaders(file_path)
