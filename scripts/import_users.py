import pandas as pd
import mysql.connector
from datetime import datetime
import os
import sys
import signal
from openpyxl.styles import PatternFill
from openpyxl.styles import PatternFill

# Configurar un timeout de 10 minutos
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

# Mapeo de estados para aprendices (con claves en mayúsculas)
# Mapeo de estados para aprendices (con claves en mayúsculas)
apprentice_state_mapping = {
    'EN FORMACION': 'En_formacion',
    'ETAPA PRODUCTIVA': 'Etapa_productiva',
    'EN COMITE': 'En_comite',
    'DESERTADO': 'Desertado',
    'RETIRO VOLUNTARIO': 'Retiro_voluntario',
    'POR CERTIFICAR': 'Por_certificar',
    'INDUCCION': 'Induccion'
    'EN FORMACION': 'En_formacion',
    'ETAPA PRODUCTIVA': 'Etapa_productiva',
    'EN COMITE': 'En_comite',
    'DESERTADO': 'Desertado',
    'RETIRO VOLUNTARIO': 'Retiro_voluntario',
    'POR CERTIFICAR': 'Por_certificar',
    'INDUCCION': 'Induccion'
}

# Mapeo de estados para instructores (con claves en mayúsculas)
# Mapeo de estados para instructores (con claves en mayúsculas)
instructor_state_mapping = {
    'ACTIVO': 'Activo',
    'INACTIVO': 'Inactivo'
    'ACTIVO': 'Activo',
    'INACTIVO': 'Inactivo'
}

def import_users(file_path):
    conn = None
    cursor = None
    failed_rows = []
    failed_indices = []
    failed_rows = []
    failed_indices = []

    try:
        # Verificar si el archivo existe
        if not os.path.exists(file_path):
            raise FileNotFoundError(f"El archivo {file_path} no existe")

        # Leer las hojas del archivo Excel
        apprentices_df = pd.read_excel(file_path, sheet_name='Aprendices')
        instructors_df = pd.read_excel(file_path, sheet_name='Instructores')

        # Conectar a la base de datos
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()

        # Procesar la hoja de Aprendices
        print("Procesando aprendices...")
        for index, row in apprentices_df.iterrows():
            try:
                # Insertar o actualizar el programa
                cursor.execute("SELECT id FROM programs WHERE code = %s", (row['CODIGO_PROGRAMA'],))
                program = cursor.fetchone()
                if not program:
                    cursor.execute(
                        "INSERT INTO programs (code, name) VALUES (%s, %s)",
                        (row['CODIGO_PROGRAMA'], row['PROGRAMA'])
                    )
                    program_id = cursor.lastrowid
                else:
                    program_id = program[0]

                # Insertar o actualizar el usuario
                cursor.execute("SELECT id FROM users WHERE identity_document = %s", (row['NUMERO_DOCUMENTO'],))
                user = cursor.fetchone()
                if not user:
                    cursor.execute(
                        "INSERT INTO users (name, last_name, identity_document, email) VALUES (%s, %s, %s, %s)",
                        (
                            row['NOMBRE'],
                            f"{row['PRIMER_APELLIDO']} {row['SEGUNDO_APELLIDO']}",
                            row['NUMERO_DOCUMENTO'],
                            row['CORREO_ELECTRONICO']
                        )
                    )
                    user_id = cursor.lastrowid
                else:
                    user_id = user[0]

                # Insertar o actualizar el curso (ficha)
                cursor.execute("SELECT id FROM courses WHERE code = %s", (row['FICHA'],))
                course = cursor.fetchone()
                if not course:
                    cursor.execute(
                        "INSERT INTO courses (code, program_id) VALUES (%s, %s)",
                        (row['FICHA'], program_id)
                    )
                    course_id = cursor.lastrowid
                else:
                    course_id = course[0]

                # Procesar el estado del aprendiz convirtiendo a mayúsculas y quitando espacios
                estado_aprendiz = str(row['ESTADO']).strip().upper()
                state_value = apprentice_state_mapping.get(estado_aprendiz, 'En_formacion')

                # Procesar el estado del aprendiz convirtiendo a mayúsculas y quitando espacios
                estado_aprendiz = str(row['ESTADO']).strip().upper()
                state_value = apprentice_state_mapping.get(estado_aprendiz, 'En_formacion')

                # Insertar o actualizar el aprendiz
                cursor.execute("SELECT id FROM apprentices WHERE user_id = %s AND course_id = %s", (user_id, course_id))
                if not cursor.fetchone():
                    cursor.execute(
                        "INSERT INTO apprentices (user_id, course_id, state) VALUES (%s, %s, %s)",
                        (user_id, course_id, state_value)
                        (user_id, course_id, state_value)
                    )

            except Exception as e:
                print(f"Error en la fila {index + 1} (Aprendices): {e}")
                print(f"Error en la fila {index + 1} (Aprendices): {e}")
                failed_rows.append(row)
                failed_indices.append(index)

        # Procesar la hoja de Instructores
        print("Procesando instructores...")
        for index, row in instructors_df.iterrows():
            try:
                # Insertar o actualizar el usuario
                cursor.execute("SELECT id FROM users WHERE identity_document = %s", (row['NUMERO_DOCUMENTO'],))
                user = cursor.fetchone()
                if not user:
                    cursor.execute(
                        "INSERT INTO users (name, last_name, identity_document, email) VALUES (%s, %s, %s, %s)",
                        (
                            row['NOMBRE'],
                            f"{row['PRIMER_APELLIDO']} {row['SEGUNDO_APELLIDO']}",
                            row['NUMERO_DOCUMENTO'],
                            row['CORREO_ELECTRONICO']
                        )
                    )
                    user_id = cursor.lastrowid
                else:
                    user_id = user[0]

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

                # Procesar el estado del instructor
                estado_instructor = str(row['ESTADO']).strip().upper()
                instructor_state = instructor_state_mapping.get(estado_instructor, 'Activo')

                # Insertar o actualizar el instructor
                cursor.execute("SELECT id FROM instructors WHERE user_id = %s", (user_id,))
                instructor = cursor.fetchone()
                if not instructor:
                    cursor.execute(
                        "INSERT INTO instructors (user_id, state, is_course_leader, knowledge_network_id) VALUES (%s, %s, %s, %s)",
                        (
                            user_id,
                            instructor_state,
                            str(row['ES_LIDER']).strip().upper() == 'SI',
                            knowledge_network_id
                        )
                    )
                    instructor_id = cursor.lastrowid
                else:
                    instructor_id = instructor[0]

                # Insertar o actualizar la relación curso-instructor
                cursor.execute("SELECT id FROM courses WHERE code = %s", (row['FICHA'],))
                course = cursor.fetchone()
                if course:
                    course_id = course[0]
                    cursor.execute(
                        "SELECT id FROM course_instructor WHERE instructor_id = %s AND course_id = %s",
                        (instructor_id, course_id)
                    )
                    if not cursor.fetchone():
                        cursor.execute(
                            "INSERT INTO course_instructor (instructor_id, course_id) VALUES (%s, %s)",
                            (instructor_id, course_id)
                        )

            except Exception as e:
                print(f"Error en la fila {index + 1} (Instructores): {e}")
                print(f"Error en la fila {index + 1} (Instructores): {e}")
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
                red_fill = PatternFill(start_color="FF0000", end_color="FF0000", fill_type="solid")

                for index in failed_indices:
                    for col in range(1, len(failed_df.columns) + 1):
                        worksheet.cell(row=index + 2, column=col).fill = red_fill
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
        print("Uso: python3 import_users.py <ruta_al_archivo>")
        sys.exit(1)

    file_path = sys.argv[1]
    file_path = sys.argv[1]
    import_users(file_path)
