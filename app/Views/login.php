<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css?v=1.0">
    <title>Halaman Login</title>
</head>

<body class="bg-[#E2E8F0]">

    <div class="bg-white min-h-screen flex items-center justify-center">
        <form action="admin" method="post" class="w-full max-w-md">
            <div class="px-8 py-10 bg-white shadow-lg rounded-xl">
                <h2 class="text-3xl font-semibold text-center mb-6">Login</h2>
                <div class="mb-6">
                    <label for="nip" class="block text-gray-600 mb-1">Email</label>
                    <input id="email" name="email" type="text" placeholder="Enter your Email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-black "required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-600 mb-1">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-black "required>
                </div>
                <div class="text-center">
                    <button type="submit" class="w-full px-6 py-3 bg-[#4318FF] text-white font-semibold rounded-md hover:bg-indigo-600 transition duration-200 ease">Submit</button>
                </div>
            </div>
        </form>
        <div class="text-center">
            <a href="admin">
            <button class="w-full px-6 py-3 bg-indigo-500 text-white font-semibold rounded-md hover:bg-indigo-600 transition duration-200 ease">Login</button>
            </a>
            <a href="signup">
            <button class="w-full px-6 py-3 bg-indigo-500 text-white font-semibold rounded-md hover:bg-indigo-600 transition duration-200 ease">Daftar</button>
            </a>
            
        </div>
    </div>




</body>

</html>