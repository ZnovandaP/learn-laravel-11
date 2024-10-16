@props(['active' => false])

 <li class="nav-item">
   <a {{ $attributes }} class="nav-link text-light {{ $active ? 'active' : '' }}"
     aria-current={{ $active ? 'page' : false }}>
     {{ $slot }}
   </a>
 </li>
