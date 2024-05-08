<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css?v=1.0">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Include TensorFlow.js library -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>

    <title><?= $title ?></title>
</head>

<body>
    <?= $this->include('components/header') ?>
    <div class="container">
        <div class="w-full h-full lg:pl-72 z-[1] overflow-clip">
            <!-- Content -->
            <?= $this->renderSection('content') ?>
            <!-- End Content -->
        </div>

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="<?= base_url('/css/preline/dist/preline.js') ?>"></script>



</body>

</html>