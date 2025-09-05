<footer class="p-4 sm:p-6 dark:bg-zinc-800 mt-auto">
    <div class="mx-auto max-w-screen-xl">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0">
                <a href="{{ route('home') }}" wire:navigate class="mr-5 flex items-center space-x-2">
                    <x-app-logo class="size-10"></x-app-logo>
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ now()->year }} &nbsp;·&nbsp; ITTux
                    </span>
                </a>
            </div>
        </div>
        <hr class="my-6 border-zinc-200 sm:mx-auto dark:border-zinc-700 lg:my-8" />
        <div class="sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-zinc-500 sm:text-center dark:text-zinc-400">
                All Rights Reserved.
            </span>
            <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                {{-- Facebook --}}
                <a href="https://www.facebook.com/tecnologicode.tuxtepec" target="_blank"
                class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 
                        8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 
                        1.492-3.89 3.777-3.89 1.094 0 2.238.195 
                        2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 
                        1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 
                        21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                    </svg>
                </a>

                {{-- Instagram --}}
                <a href="https://www.instagram.com/tecnm_tuxtepec" target="_blank"
                class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 
                        3.808.06 1.064.049 1.791.218 2.427.465a4.902 
                        4.902 0 011.772 1.153 4.902 4.902 0 
                        011.153 1.772c.247.636.416 1.363.465 
                        2.427.048 1.067.06 1.407.06 4.123v.08c0 
                        2.643-.012 2.987-.06 4.043-.049 1.064-.218 
                        1.791-.465 2.427a4.902 4.902 0 01-1.153 
                        1.772 4.902 4.902 0 01-1.772 
                        1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 
                        0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 
                        4.902 0 01-1.772-1.153 4.902 4.902 0 
                        01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 
                        4.902 0 011.153-1.772A4.902 4.902 0 
                        015.45 2.525c.636-.247 1.363-.416 
                        2.427-.465C8.901 2.013 9.256 2 
                        11.685 2h.63zM12 6.865a5.135 5.135 
                        0 110 10.27 5.135 5.135 0 
                        010-10.27zm0 1.802a3.333 3.333 
                        0 100 6.666 3.333 3.333 0 
                        000-6.666zm5.338-3.205a1.2 1.2 
                        0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                    </svg>
                </a>

                {{-- Página web --}}
                <a href="https://www.tuxtepec.tecnm.mx/" target="_blank"
                class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 
                        2 12s4.477 10 10 10 10-4.477 
                        10-10S17.523 2 12 2zm0 2c1.657 
                        0 3.156.672 4.243 1.757A5.985 
                        5.985 0 0120 12a5.985 5.985 0 
                        01-3.757 6.243A8.001 8.001 0 
                        0112 20a8.001 8.001 0 
                        01-4.243-1.757A5.985 5.985 0 
                        014 12a5.985 5.985 0 
                        013.757-6.243A8.001 8.001 0 
                        0112 4z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>