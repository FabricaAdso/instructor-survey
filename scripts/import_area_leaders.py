import pandas as pd
import mysql.connector
import os
import sys
import signal
from openpyxl.styles import PatternFill

# Configurar un timeout de 10 minutos
signal.signal(signal.SIGALRM, lambda signum, frame: print("Tiempo de ejecución excedido"))
signal.alarm(600)  # 600 segundos (10 minutos)

# Configuración de la base de datos
db_config = {
    'host': 'localhost',
    'user': 'root',           # usuario de MySQL
    'password': 'fabrica123',  # contraseña de MySQL
    'database': 'instructor_survey'  # nombre de la base de datos
}

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
        for index, row in leaders_df.iterrows():
            try:
                # Insertar o actualizar el usuario (con is_area_leader = True)
                cursor.execute("SELECT id FROM users WHERE identity_document = %s", (row['NUMERO_DOCUMENTO'],))
                user = cursor.fetchone()

                if not user:
                    # Insertar nuevo usuario como líder de área
                    cursor.execute(
                        """INSERT INTO users
                        (identity_document, name, last_name, email, is_area_leader)
                        VALUES (%s, %s, %s, %s, %s)""",
                        (
                            row['NUMERO_DOCUMENTO'],
                            row['NOMBRE'],
                            f"{row['PRIMER_APELLIDO']} {row['SEGUNDO_APELLIDO']}",
                            row['CORREO_ELECTRONICO'],
                            True  # Marcamos como líder de área
                        )
                    )
                    user_id = cursor.lastrowid
                else:
                    user_id = user[0]
                    # Actualizar usuario existente como líder de área
                    cursor.execute(
                        """UPDATE users
                        SET is_area_leader = TRUE
                        WHERE id = %s""",
                        (user_id,)
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
