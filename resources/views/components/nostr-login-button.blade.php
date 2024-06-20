<div>
    <button id="login" class="btn btn-primary">
        {{ __('Login with Nostr') }}
    </button>
</div>
@push('scripts')
<script>
    document.getElementById('login').addEventListener('click', async () => {
        if (!window.nostr) {
            alert('Nostr extension not found');
            return;
        }

        const pubkey = await window.nostr.getPublicKey();
        const createdAt = Math.floor(Date.now() / 1000)
        const event = {
            created_at: createdAt,
            kind: 12345,
            tags: [],
            content: 'Log in with Nostr key.'
        };
        const signedEvent = await window.nostr.signEvent(event);

        const signature = signedEvent['sig'];
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/nostr-login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(signedEvent),
        })
        .then(response => response.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
    });
</script>
@endpush