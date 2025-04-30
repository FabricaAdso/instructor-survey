<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Instructores</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->

    <style>
        /* Global Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1F2937;
            padding: 10px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 16px;
        }

        h3 {
            margin: 0;
            font-size: 1rem;
            margin-bottom: 10px;
            color: #388E3C;
        }

        /* Botones */
        .btn {
            padding: 5px 5px;
            background-color: #388E3C;
            color: #fff;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            border: 2px solid #2E7D32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        /* Toasts */
        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            color: #fff;
            z-index: 1000;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-out 2.5s;
        }

        .toast-success {
            background-color: #38a901;
        }

        .toast-error {
            background-color: #e53e3e;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        /* Header Flex */
        .header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
        }

        .left-group,
        .right-group {
            flex: 0 0 250px;
        }

        .center-group {
            flex: 1;
            text-align: center;
        }

        @media (max-width: 920px) and (min-width: 801px) {
            .right-group {
                flex: 0 0 100px;
            }
        }

        @media (max-width: 800px) {
            .header-flex {
                flex-wrap: wrap !important;
            }

            .left-group,
            .center-group,
            .right-group {
                flex: 1 0 100%;
                text-align: center;
                margin-bottom: 10px;
            }

            .right-group {
                display: none;
            }
        }    

        /* Grupos y botones de la cabecera */
        .button-group {
            width: 320px;
        }


    </style>
</head>

<body>
@include('admin.menu.header')

    <div class="container index-container">
        <h3>Reporte de Instructores</h3>

        <div class="header-flex">
            <div class="left-group">
                <div class="button-group">
                @include('admin.reports.survey-toggle')
                @include('admin.menu.uploadButtton')



                    
                </div>
            </div>

            <div class="center-group">
            @include('admin.reports.searchInstructor')
              

            </div>

            <div class="right-group"></div>
        </div>

        <br>

        <div class="table-container"></div>
    </div>

    <script>

function openInstructorModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) modal.classList.add('show');
    else console.error('Modal no encontrado para el ID:', id);
  }

  function closeInstructorModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) modal.classList.remove('show');
    else console.error('Modal no encontrado para el ID:', id);
  }

    // notificaciones emergentes temporales
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

</script>
</body>

</html>
