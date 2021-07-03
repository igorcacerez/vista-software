<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SITE_NOME; ?></title>
</head>
<body>

    <script>
        localStorage.clear();

        setTimeout(() => {
            location.href = "<?= BASE_URL; ?>login";
        }, 300);
    </script>

</body>
</html>