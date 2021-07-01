<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-200">
    {{-- <p>Hi {{ $user->username }},</p>
    <p>Please click the below link to activate your account</p>
    <p>Token - {{ $token }}</p --}}
    <header class="text-gray-600 body-font bg-white">
        <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
            <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="ml-3 text-xl">Sense</span>
            </a>
        </div>
    </header>   


    <section class="text-gray-600 body-font">
        <div class="container px-5 py-24 sm:mx-auto mt-12 shadow-md bg-white rounded-md">
          <div class="flex flex-col text-center w-full ">
            <h2 class="text-md text-indigo-500 tracking-widest font-medium title-font mb-1">From: MAIL.SENSE.COM</h2>
            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">One time email verification</h1>
            <p class="lg:w-2/3 mx-auto leading-relaxed text-gray-600 text-lg">
                Hi, sharthakmallik@gmail.com. A warm welcome to Sense team.   
            </p>
            <p class="lg:w-2/3 mx-auto leading-relaxed text-gray-600 text-lg">
                Use this OTP to vertify your email.
            </p>
            <p class="mt-5" style="font-size: 33px">
                {{ $token }}
            </p>
            <p class="mt-11"> 
                <b>Note : </b> <span>Do not share this code with any other person</span> 
            </p>
          </div>
          
        </div>
      </section>
</body>
</html>