<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ternakku</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }
    </style>
</head>


<body class="bg-gray-100 min-h-screen">
    @include('layouts.admin.header')

    <div class="flex flex-col min-h-screen">
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 py-8">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-1">Ternakku</h3>
            </div>
            @include('layouts.admin.menu_cards')
        </main>
    </div>



    </main>
    </div>

    @include('layouts.admin.logout_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdown = document.getElementById('profileDropdown');
            const logoutBtn = document.getElementById('logoutBtn');
            const modal = document.getElementById('logoutModal');
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && e.target !== dropdownBtn) {
                    dropdown.classList.add('hidden');
                }
            });
            logoutBtn.addEventListener('click', function() {
                dropdown.classList.add('hidden');
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'pointer-events-none');
                    modal.classList.add('opacity-100');
                }, 10);
            });
        });

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            modal.classList.add('pointer-events-none');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 150);
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") closeLogoutModal();
        });
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('logoutModal');
            if (modal.style.display === 'flex' && e.target === modal) {
                closeLogoutModal();
            }
        });
    </script>

</body>

</html>
