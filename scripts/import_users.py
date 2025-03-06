import pandas as pd
import mysql.connector
from datetime import datetime
import os
import sys
import signal

signal.signal(signal.SIGALRM, lambda signum, frame: print("Tiempo de ejecución excedido"))
signal.alarm(600)  # 600 segundos (10 minutos)

# Configuración de la base de datos
db_config = {
    'host': 'localhost',
    'user': 'root',  # Cambia por tu usuario de MySQL
    'password': 'fabrica123',  # Cambia por tu contraseña de MySQL
    'database': 'instructor_survey'  # Cambia por el nombre de tu base de datos
}

# Mapeo de estados para aprendices
apprentice_state_mapping = {
    'Formacion': 'Formacion',
    'Etapa productiva': 'Etapa_productiva',
    'En comite': 'En_comite',
    'Desertado': 'Desertado',
    'Retiro voluntario': 'Retiro_voluntario'
}

# Mapeo de estados para instructores
instructor_state_mapping = {
    'Activo': 'Activo',
    'Inactivo': 'Inactivo'
}

def import_users(file_path):
    conn = None
    cursor = None
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
            print(f"Procesando aprendiz {index + 1}: {row}")  # Depuración

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

            # Insertar o actualizar el aprendiz
            cursor.execute("SELECT id FROM apprentices WHERE user_id = %s AND course_id = %s", (user_id, course_id))
            if not cursor.fetchone():
                cursor.execute(
                    "INSERT INTO apprentices (user_id, course_id, state) VALUES (%s, %s, %s)",
                    (user_id, course_id, apprentice_state_mapping.get(row['ESTADO'], 'Formacion'))
                )

        # Procesar la hoja de Instructores
        print("Procesando instructores...")
        for index, row in instructors_df.iterrows():
            print(f"Procesando instructor {index + 1}: {row}")  # Depuración

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

            # Insertar o actualizar el instructor
            cursor.execute("SELECT id FROM instructors WHERE user_id = %s", (user_id,))
            instructor = cursor.fetchone()
            if not instructor:
                cursor.execute(
                    "INSERT INTO instructors (user_id, state, is_course_leader) VALUES (%s, %s, %s)",
                    (user_id, instructor_state_mapping.get(row['ESTADO'], 'Activo'), row['ES_LIDER'] == 'SI')
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
                    "INSERT INTO course_instructor (instructor_id, course_id) VALUES (%s, %s)",
                    (instructor_id, course_id)
                )

        conn.commit()
        print("Archivo importado correctamente")
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

    file_path = sys.argv[1]  # Ruta del archivo Excel
    import_users(file_path)
