<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 border border-transparent rounded-[1em] font-extrabold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-blue-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-0.5 w-full justify-center']) }}>
    {{ $slot }}
</button>
