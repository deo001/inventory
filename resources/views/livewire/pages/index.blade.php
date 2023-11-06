<div>
    <x-slot:content>
        <div class="relative h-80 bg-cover bg-center rounded flex justify-center items-center" style="background-image: url('/img/home.jpg')">
            <div class="absolute top-0 h-full w-full flex justify-center items-center bg-gradient-to-r from-cyan-500/50 to-blue-500/5">
                <div>
                    <p class="text-white text-4xl text-center my-2">Discover topic on <span>Blog</span></p>
                    <p class="text-slate-300 text-lg text-center tracking-tight mt-2">Here is your place to get updated on different topics</p>
                    <div class="flex justify-center mt-4">
                        <button class="bg-sky-600/90 text-white rounded-full py-2 px-4 hover:bg-sky-500/80 focus:ring-1">Get Started</button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="mx-auto max-w-2xl sm:text-center mb-12">
            <h1 class="text-3xl text-center my-2">Latest Topics</h1>
            <p class="text-lg mt-2 text-slate-600 text-center">Explore more on post link to discover more topics you are interested with</p>
        </div>
        <div class="grid grid-cols-1 gap-x-8 gap-y-16 lg:grid-cols-3">
            @foreach ($posts as $post)
                <x-posts.card :$post />
            @endforeach
        </div>
    </div>
</div>
