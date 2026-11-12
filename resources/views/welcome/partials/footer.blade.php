<!-- TECH DARK FOOTER -->
<footer id="contact" class="tech-footer-section">
    <div class="tech-container">
        <!-- Contact Terminal Box -->
        <div class="contact-terminal-box">
            <div class="terminal-box-header">
                <div class="terminal-dots">
                    <span class="dot dot-red"></span>
                    <span class="dot dot-yellow"></span>
                    <span class="dot dot-green"></span>
                    <span class="terminal-title font-mono">contact.init()</span>
                </div>
                <div class="terminal-status font-mono text-amber-400 text-xs">
                    <span class="status-pulse-dot"></span> PING OK
                </div>
            </div>

            <div class="terminal-box-body">
                <div class="contact-prompt font-mono text-amber-400 mb-2">// 05. CONNECT & COLLABORATE</div>
                <h3 class="contact-heading">Let's build something remarkable together.</h3>
                <p class="contact-subheading">
                    Whether you're looking for engineering leadership, system architecture, or full-stack development, feel free to reach out.
                </p>

                <!-- Social Icons Links -->
                <div class="social-links-grid">
                    @foreach ($data['socials'] as $social)
                        <a href="{{ $social->link }}" target="_blank" rel="noopener noreferrer" class="social-tech-badge" title="{{ $social->name }}">
                            <i class="{{ $social->allicons->fullname ?? 'fas fa-link' }}"></i>
                            <span class="social-name">{{ $social->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System & Copyright Subfooter -->
        <div class="tech-subfooter">
            <div class="subfooter-copy">
                <span>© {{ date('Y') }} <strong>{{ $data['name'] }}</strong></span>
                <span class="separator">•</span>
                <span>Powered by <a href="https://mushmero.com" target="_blank" rel="noopener" class="text-amber-400 hover:underline">Resumed</a> by Mushmero</span>
                <span class="separator">•</span>
                <span class="font-mono text-xs text-slate-500">v{{ Helper::appVer() }}</span>
            </div>

            <div class="subfooter-links font-mono text-xs">
                @if (env('APP_ENV') == 'local')
                    <span class="text-slate-500">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</span>
                    @if (Route::has('telescope'))
                        <span class="separator">|</span>
                        <a href="{{ route('telescope') }}" target="_blank" class="text-slate-400 hover:text-white">Telescope</a>
                    @endif
                    <span class="separator">|</span>
                    @auth
                        <a href="{{ route('home') }}" class="text-amber-400 hover:underline"><i class="fas fa-tachometer-alt mr-1"></i> Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-white"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                    @endauth
                @elseif (config('app.is_demo'))
                    @auth
                        <a href="{{ route('home') }}" class="text-amber-400 hover:underline"><i class="fas fa-tachometer-alt mr-1"></i> Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-white"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</footer>
