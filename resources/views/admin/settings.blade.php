@extends('layouts.admin')
@section('title', 'API Settings')

@section('content')
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('llm')">🤖 LLM Provider</button>
    <button class="tab-btn" onclick="switchTab('geocoding')">🌍 Geocoding</button>
    <button class="tab-btn" onclick="switchTab('webhook')">🔗 Webhook</button>
    <button class="tab-btn" onclick="switchTab('map')">🗺️ Map</button>
    <button class="tab-btn" onclick="switchTab('general')">⚙️ General</button>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <!-- LLM Settings -->
    <div class="tab-content active" id="tab-llm">
        <div class="card">
            <h3 style="margin-bottom:20px;font-size:16px;">LLM / AI Provider</h3>

            <div class="form-group">
                <label class="form-label">Provider</label>
                <select name="llm_provider" class="form-select" id="llm-provider-select">
                    @php $provider = $settings['llm']->firstWhere('setting_key', 'llm_provider')->setting_value ?? 'ollama'; @endphp
                    <option value="ollama" {{ $provider == 'ollama' ? 'selected' : '' }}>🦙 Ollama (Self-hosted)</option>
                    <option value="openai" {{ $provider == 'openai' ? 'selected' : '' }}>🟢 OpenAI</option>
                    <option value="gemini" {{ $provider == 'gemini' ? 'selected' : '' }}>🔵 Google Gemini</option>
                    <option value="anthropic" {{ $provider == 'anthropic' ? 'selected' : '' }}>🟣 Anthropic (Claude)</option>
                    <option value="deepseek" {{ $provider == 'deepseek' ? 'selected' : '' }}>🐋 DeepSeek</option>
                    <option value="groq" {{ $provider == 'groq' ? 'selected' : '' }}>⚡ Groq</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">API URL</label>
                <input type="text" name="llm_api_url" class="form-input"
                    value="{{ $settings['llm']->firstWhere('setting_key', 'llm_api_url')->setting_value ?? '' }}"
                    placeholder="http://localhost:11434">
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                    Ollama: http://localhost:11434 | OpenAI/Groq/DeepSeek: Cukup gunakan base URL | Gemini: https://generativelanguage.googleapis.com | Anthropic: https://api.anthropic.com
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">API Key</label>
                <input type="password" name="llm_api_key" class="form-input"
                    value="{{ $settings['llm']->firstWhere('setting_key', 'llm_api_key')->setting_value ?? '' }}"
                    placeholder="sk-... atau AIza...">
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                    Kosongkan untuk Ollama (tidak perlu API key)
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Model</label>
                <input type="text" name="llm_model" class="form-input"
                    value="{{ $settings['llm']->firstWhere('setting_key', 'llm_model')->setting_value ?? '' }}"
                    placeholder="llama3, gpt-4o-mini, gemini-pro">
            </div>
        </div>
    </div>

    <!-- Geocoding Settings -->
    <div class="tab-content" id="tab-geocoding">
        <div class="card">
            <h3 style="margin-bottom:20px;font-size:16px;">Geocoding Provider</h3>

            <div class="form-group">
                <label class="form-label">Provider</label>
                <select name="geocoding_provider" class="form-select">
                    @php $geo = $settings['geocoding']->firstWhere('setting_key', 'geocoding_provider')->setting_value ?? 'nominatim'; @endphp
                    <option value="nominatim" {{ $geo == 'nominatim' ? 'selected' : '' }}>🗺️ Nominatim (Free - OpenStreetMap)</option>
                    <option value="google" {{ $geo == 'google' ? 'selected' : '' }}>🔵 Google Maps</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">API Key (Google Maps only)</label>
                <input type="password" name="geocoding_api_key" class="form-input"
                    value="{{ $settings['geocoding']->firstWhere('setting_key', 'geocoding_api_key')->setting_value ?? '' }}"
                    placeholder="AIza...">
            </div>
        </div>
    </div>

    <!-- Webhook Settings -->
    <div class="tab-content" id="tab-webhook">
        <div class="card">
            <h3 style="margin-bottom:20px;font-size:16px;">Webhook (n8n Integration)</h3>

            <div class="form-group">
                <label class="form-label">Webhook URL</label>
                <div class="webhook-display">
                    {{ url('/api/webhook/events') }}
                </div>
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                    Gunakan URL ini di n8n HTTP Request node
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">API Key</label>
                <div class="webhook-display" style="display:flex;justify-content:space-between;align-items:center;">
                    <span id="webhook-key">{{ $settings['webhook']->firstWhere('setting_key', 'webhook_api_key')->setting_value ?? '' }}</span>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="copyWebhookKey()" style="flex-shrink:0;">
                        <i class="ri-file-copy-line"></i> Copy
                    </button>
                </div>
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                    Kirim sebagai header: <code style="background:var(--bg-primary);padding:2px 6px;border-radius:4px;">X-API-Key: [key]</code>
                </small>
            </div>
        </div>

        <div style="margin-top:16px;">
            <form method="POST" action="{{ route('admin.settings.regenerate-webhook') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Generate ulang API key? Key lama tidak akan berfungsi.')">
                    <i class="ri-refresh-line"></i> Regenerate API Key
                </button>
            </form>
        </div>
    </div>

    <!-- Map Settings -->
    <div class="tab-content" id="tab-map">
        <div class="card">
            <h3 style="margin-bottom:20px;font-size:16px;">Map Settings</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label">Center Latitude</label>
                    <input type="number" step="any" name="map_center_lat" class="form-input"
                        value="{{ $settings['map']->firstWhere('setting_key', 'map_center_lat')->setting_value ?? '-0.7893' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Center Longitude</label>
                    <input type="number" step="any" name="map_center_lng" class="form-input"
                        value="{{ $settings['map']->firstWhere('setting_key', 'map_center_lng')->setting_value ?? '113.9213' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Default Zoom</label>
                    <input type="number" name="map_zoom" class="form-input" min="1" max="18"
                        value="{{ $settings['map']->firstWhere('setting_key', 'map_zoom')->setting_value ?? '5' }}">
                </div>
            </div>
        </div>
    </div>

    <!-- General Settings -->
    <div class="tab-content" id="tab-general">
        <div class="card">
            <h3 style="margin-bottom:20px;font-size:16px;">General Settings</h3>

            <div class="form-group">
                <label class="form-label">Polling Interval (detik)</label>
                <input type="number" name="polling_interval" class="form-input" min="5" max="300"
                    value="{{ $settings['general']->firstWhere('setting_key', 'polling_interval')->setting_value ?? '30' }}">
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                    Interval auto-refresh peta (dalam detik)
                </small>
            </div>
        </div>
    </div>

    <div style="margin-top:24px;">
        <button type="submit" class="btn btn-primary">
            <i class="ri-save-line"></i> Simpan Pengaturan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    event.target.classList.add('active');
}

function copyWebhookKey() {
    const key = document.getElementById('webhook-key').textContent;
    navigator.clipboard.writeText(key).then(() => {
        alert('API Key berhasil di-copy!');
    });
}
</script>
@endpush
