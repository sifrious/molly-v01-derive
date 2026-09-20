<div>
    @auth
        <h1>Hello world</h1>
        <button wire:click="increment" data-increment>Increment</button>
        <span data-count="{{ $count }}"></span>
    @else
        <h1>Hello stranger</h1>
        <div data-counter-absent></div>
    @endauth
</div>
