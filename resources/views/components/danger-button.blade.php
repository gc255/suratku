<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-aksi-red inline-flex items-center justify-center font-bold px-5 py-2.5 rounded-xl shadow-lg text-xs uppercase tracking-wider transition cursor-pointer']) }}>
    {{ $slot }}
</button>
