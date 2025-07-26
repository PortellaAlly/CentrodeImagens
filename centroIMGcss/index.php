<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Centro de Imagens</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'VT323', monospace;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen p-8">

    <?php
    if (isset($_POST['acao'])) {
        $arquivo = $_FILES['file'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        echo '<div class="mb-4">';
        if (!in_array($extensao, $permitidas)) {
            echo "<p class='text-red-400'>❌ Esse tipo de arquivo não é permitido.</p>";
        } else {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
            $caminhoFinal = 'uploads/' . basename($arquivo['name']);
            if (move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
                echo "<p class='text-green-400'>✅ Arquivo enviado com sucesso!</p>";
            } else {
                echo "<p class='text-red-400'>❌ Erro ao mover o arquivo.</p>";
            }
        }
        echo '</div>';
    }
    ?>

    <h1 class="text-4xl mb-6 text-center text-yellow-300">Centro de Imagens</h1>

    <form method="post" action="" enctype="multipart/form-data" class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md mx-auto mb-12">
        <label class="block text-lg mb-2" for="file">Selecione uma imagem:</label>
        <input type="file" name="file" id="file" required class="mb-4 w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded">

        <button type="submit" name="acao" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full transition-all duration-200">
            🚀 Enviar imagem
        </button>
    </form>

    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl mb-4 text-center">Imagens enviadas</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            $imagens = glob('uploads/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            if (count($imagens) > 0) {
                foreach ($imagens as $imagem) {
                    echo "<div class='bg-gray-800 p-2 rounded-lg shadow hover:scale-105 transition-all'>";
                    echo "<img src='$imagem' alt='Imagem' class='w-full h-48 object-cover rounded cursor-zoom-in zoomable-img'>";
                    echo "</div>";
                }
            } else {
                echo "<p class='col-span-full text-center text-gray-400'>Nenhuma imagem enviada ainda.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Overlay para visualização expandida -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-80 hidden z-50 flex items-center justify-center p-4">
        <img id="overlay-img" src="" class="rounded shadow-lg transition-all duration-300 w-[90%] h-auto md:w-auto md:max-h-[90vh]">
    </div>


    <script>
        const overlay = document.getElementById('overlay');
        const overlayImg = document.getElementById('overlay-img');

        // Ativa o zoom
        document.querySelectorAll('.zoomable-img').forEach(img => {
            img.addEventListener('click', () => {
                overlayImg.src = img.src;
                overlay.classList.remove('hidden');
            });
        });

        // Fecha ao clicar no fundo ou pressionar ESC
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay || e.target === overlayImg) {
                overlay.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                overlay.classList.add('hidden');
            }
        });
    </script>

</body>

</html>