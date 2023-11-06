<div class="bg-gray-300 py-6 mt-10 sm:py-8">
    <div class="flex justify-evenly sm:justify-center flex-wrap ">
        <div>
            <div class="ms-4 flex flex-col sm:flex-row space-x-4">
                <a wire:navigate href="{{ route('home') }}">Home</a>
                <a wire:navigate href="{{ route('post.index') }}">Posts</a>
            </div>
        </div>
        <div>
            <div class="ms-4 flex flex-col sm:flex-row space-x-4">
                <a wire:navigate href="{{ route('home') }}">Login</a>
                <a wire:navigate href="{{ route('post.index') }}">Register</a>
            </div>
        </div>
    </div>
    <p class="text-center sm:py-2">©2023 blog. All rights reserved</p>
</div>