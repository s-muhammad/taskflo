<div x-data="{ subscribed: false, loading: false }" x-init="(async () => { try { let r = await fetch('{{ route('webpush.status') }}'); subscribed = (await r.json()).subscribed; } catch(e){} })()">
    <template x-if="!subscribed">
        <button @click="
            loading=true;
            try {
                let reg = await navigator.serviceWorker.register('/sw.js');
                await navigator.serviceWorker.ready;
                let existing = await reg.pushManager.getSubscription();
                if (existing) await existing.unsubscribe();
                let p = await Notification.requestPermission();
                if (p !== 'granted') { loading=false; return; }
                let key = '{{ config('webpush.vapid.public_key') }}';
                let pad = '='.repeat((4 - key.length % 4) % 4);
                let b64 = (key + pad).replace(/-/g,'+').replace(/_/g,'/');
                let raw = window.atob(b64);
                let arr = new Uint8Array(raw.length);
                for (let i=0; i<raw.length; i++) arr[i] = raw.charCodeAt(i);
                let sub = await reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: arr });
                let res = await fetch('{{ route('webpush.subscribe') }}', {
                    method:'POST',
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=\'csrf-token\']').content},
                    body: JSON.stringify(sub)
                });
                if (res.ok) subscribed=true;
            } catch(e) { console.error(e); }
            loading=false;
        " :disabled="loading" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg disabled:opacity-50 transition-colors">
            <span x-text="loading ? '...' : 'فعال‌سازی یادآوری وظایف'"></span>
        </button>
    </template>
    <template x-if="subscribed">
        <span class="text-sm text-green-600 font-medium">✅ اعلان فعال است</span>
    </template>
</div>
