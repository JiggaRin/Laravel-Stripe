<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

</head>
<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

<div style="display: flex; gap: 3rem">
    @foreach($products as $product)
        <div style="flex: 1">
            <img src="{{$product->image}}" alt="" style="max-width: 100%">
            <h5>{{$product->name}}</h5>
            <p>{{$product->price}}</p>
        </div>
    @endforeach
</div>
<p>
<form action="{{route('checkout')}}" method="POST">
    @csrf
    <button>Checkout</button>
</form>
</p>
</body>
</html>
