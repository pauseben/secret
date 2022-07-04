Secret data:
    @foreach ($secret as $s)
        {{ Crypt::decryptString($s->secretText) }}
    @endforeach
