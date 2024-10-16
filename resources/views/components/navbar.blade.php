 <nav class="navbar navbar-expand-lg bg-primary">
   <div class="container px-4">
     <a class="navbar-brand text-light fw-semibold" href="/">goBlog</a>
     <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
       aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
     </button>
     <div class="navbar-collapse collapse" id="navbarNav">
       <ul class="navbar-nav ms-auto">
         <x-navlink :active="request()->is('/')" href="/">Home</x-navlink>
         <x-navlink :active="request()->is('blog')" href="/blog">blog</x-navlink>
         <x-navlink :active="request()->is('dashboard')" href="/dashboard">Dashboard</x-navlink>
         <x-navlink :active="request()->is('contact')" href="/contact">Contact</x-navlink>
       </ul>
     </div>
   </div>
 </nav>
