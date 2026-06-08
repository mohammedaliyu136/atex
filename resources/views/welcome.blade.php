@extends('layouts.landing')
@section('content')
      <section id="home" class="view active">
        <section class="hero">
          <div class="hero-media" aria-hidden="true"></div>
          <div class="hero-shell">
            <div class="hero-copy">
              <span class="eyebrow" data-hero-eyebrow>Adamawa State Export Facilitation</span>
              <h1 data-hero-title>Grow Adamawa exports with one trusted trade platform</h1>
              <p>
                <span data-hero-copy>
                Connect verified local exporters with global buyers through a digital trade
                flow for product discovery, seller onboarding, AfriBridge-managed fulfillment, and compliance.
                </span>
              </p>
              <div class="hero-actions">
                <a class="btn primary" href="{{ route('register') }}">Register as Exporter</a>
                <a class="btn light" href="#marketplace">Find Products</a>
              </div>
              <div class="hero-points">
                <span><i data-lucide="badge-check"></i> Verified exporters</span>
                <span><i data-lucide="shield-check"></i> Compliance-first workflow</span>
                <span><i data-lucide="ship"></i> Shipment visibility</span>
              </div>
              <div class="hero-slider-controls" aria-label="Landing page slides">
                <div class="hero-dots" data-hero-dots></div>
              </div>
            </div>
          </div>
        </section>

        <section class="band search-band">
          <div class="hero-search-wrap">
            <aside class="hero-search-panel">
              <span class="eyebrow">Product And Partner Search</span>
              <h2>Find export-ready suppliers faster</h2>
              <p>Search by product, sector, destination readiness, and exporter profile from one marketplace.</p>
              <div class="hero-search" role="search">
                <div class="search-field">
                  <i data-lucide="search"></i>
                  <input type="search" id="heroSearchInput" placeholder="Search sesame, leather, hibiscus, shea..." />
                </div>
                <label class="hero-select">
                  <span>Category</span>
                  <select id="heroCategorySelect">
                    <option>All sectors</option>
                    <option>Agriculture</option>
                    <option>Food Processing</option>
                    <option>Textiles</option>
                    <option>Minerals</option>
                  </select>
                </label>
                <button class="btn primary" type="button" id="heroSearchBtn">Search</button>
              </div>
              <div class="hero-search-meta">
                <span>248 verified exporters</span>
                <span>1,420 export-ready listings</span>
              </div>
            </aside>
          </div>
        </section>

        <section class="band hero-band">
          <div class="metric-row">
            <article>
              <strong>248</strong>
              <span>verified exporters</span>
              <small>approved business profiles</small>
            </article>
            <article>
              <strong>1,420</strong>
              <span>export-ready listings</span>
              <small>catalogued for buyers</small>
            </article>
            <article>
              <strong>37</strong>
              <span>buyer destination countries</span>
              <small>active regional and global demand</small>
            </article>
            <article>
              <strong>96%</strong>
              <span>document review SLA met</span>
              <small>faster compliance turnaround</small>
            </article>
          </div>
        </section>

        <section class="content-grid two market-overview">
          <div class="section-stack">
            <div class="section-heading">
              <span class="eyebrow">Featured Categories</span>
              <h2>Products ready for regional and global markets</h2>
              <p>Built to make discovery feel easier for buyers while giving Adamawa sellers a stronger digital shelf and a managed fulfillment path.</p>
            </div>
            <div class="category-grid">
              <button class="category-card" type="button" onclick="window.location.hash='#marketplace'; document.getElementById('categoryFilter').value='Agriculture'; triggerMarketplaceFilter();">
                <i data-lucide="wheat"></i>
                <strong>Agricultural Produce</strong>
                <span>Sesame, maize, rice, sorghum</span>
              </button>
              <button class="category-card" type="button" onclick="window.location.hash='#marketplace'; document.getElementById('categoryFilter').value='Food Processing'; triggerMarketplaceFilter();">
                <i data-lucide="package-check"></i>
                <strong>Processed Foods</strong>
                <span>Spices, honey, dried goods</span>
              </button>
              <button class="category-card" type="button" onclick="window.location.hash='#marketplace'; document.getElementById('categoryFilter').value='Textiles'; triggerMarketplaceFilter();">
                <i data-lucide="shirt"></i>
                <strong>Textiles & Crafts</strong>
                <span>Woven fabrics, leatherwork</span>
              </button>
              <button class="category-card" type="button" onclick="window.location.hash='#marketplace'; document.getElementById('categoryFilter').value='Minerals'; triggerMarketplaceFilter();">
                <i data-lucide="gem"></i>
                <strong>Solid Minerals</strong>
                <span>Verified mineral suppliers</span>
              </button>
            </div>

            <div class="offer-strip">
              <article class="offer-card">
                <span class="offer-icon"><i data-lucide="search-check"></i></span>
                <div>
                  <h3>Seller program discovery</h3>
                  <p>Help producers and exporters join a branded seller program with clearer product visibility and partner matching.</p>
                </div>
              </article>
              <article class="offer-card">
                <span class="offer-icon"><i data-lucide="file-check-2"></i></span>
                <div>
                  <h3>Compliance-led approvals</h3>
                  <p>Keep product visibility tied to document review, KYC validation, and approval status.</p>
                </div>
              </article>
              <article class="offer-card">
                <span class="offer-icon"><i data-lucide="truck"></i></span>
                <div>
                  <h3>AfriBridge fulfillment continuity</h3>
                  <p>Let sellers ship stock to AfriBridge so the platform can accept, store, fulfill, and reconcile orders end to end.</p>
                </div>
              </article>
            </div>
          </div>
          <div class="trust-stack">
            <aside class="trust-panel">
              <div class="section-heading compact section-heading-lined">
                <span class="eyebrow">Trade Assurance</span>
                <h2>Verification built into every transaction</h2>
              </div>
              <ul class="check-list">
                <li><i data-lucide="badge-check"></i> Exporter identity and business validation</li>
                <li><i data-lucide="file-check-2"></i> Product certificate and document review</li>
                <li><i data-lucide="shield-check"></i> Payment status and dispute controls</li>
                <li><i data-lucide="ship"></i> Logistics milestones and shipment visibility</li>
              </ul>
            </aside>

            <aside class="highlight-panel">
              <span class="eyebrow">Why It Works</span>
              <h3>One seller program with managed fulfillment</h3>
              <p>Instead of leaving exporters to handle every shipment themselves, AfriBridge can receive branded inventory, fulfill buyer orders, deduct commission and taxes, and credit seller balances.</p>
              <div class="highlight-stats">
                <article><strong>Accepted</strong><span>Seller inventory is received into the fulfillment workflow</span></article>
                <article><strong>Fulfilled</strong><span>AfriBridge ships approved marketplace orders on the seller's behalf</span></article>
                <article><strong>Settled</strong><span>Seller accounts are credited after commission and tax deductions</span></article>
              </div>
            </aside>
          </div>
        </section>

        <section class="band value-band">
          <div class="section-heading section-heading-center section-heading-lined">
            <span class="eyebrow">Our Seller Program</span>
            <h2>Everything needed to list, fulfill, and settle with more confidence</h2>
          </div>
          <div class="value-grid">
            <article class="value-card">
              <i data-lucide="store"></i>
              <strong>Marketplace visibility</strong>
              <p>Present branded products, certifications, and readiness indicators in a buyer-friendly format.</p>
            </article>
            <article class="value-card">
              <i data-lucide="warehouse"></i>
              <strong>Fulfillment by AfriBridge</strong>
              <p>Sellers ship stock to AfriBridge and the platform handles receipt, storage, packing, and order fulfillment.</p>
            </article>
            <article class="value-card">
              <i data-lucide="wallet"></i>
              <strong>Settlement and payout ledger</strong>
              <p>Track commissions, taxes, and seller credits so every fulfilled order resolves into a clear payout balance.</p>
            </article>
          </div>
        </section>
      </section>

      <section id="marketplace" class="view">
        <div class="page-shell">
          <div class="section-heading">
            <span class="eyebrow">Marketplace</span>
            <h2>Export-ready product catalog</h2>
          </div>

          <div class="market-layout">
            <aside class="filters">
              <label>
                Search
                <div class="input-with-icon">
                  <i data-lucide="search"></i>
                  <input id="productSearch" type="search" placeholder="Product or exporter" />
                </div>
              </label>
              <label>
                Category
                <select id="categoryFilter">
                  <option value="all">All categories</option>
                  <option value="Agriculture">Agriculture</option>
                  <option value="Food Processing">Food Processing</option>
                  <option value="Textiles">Textiles</option>
                  <option value="Minerals">Minerals</option>
                </select>
              </label>
              <label>
                Certification
                <select>
                  <option>Any certificate</option>
                  <option>NAFDAC</option>
                  <option>Phytosanitary</option>
                  <option>Organic</option>
                  <option>SONCAP</option>
                </select>
              </label>
              <label>
                Minimum readiness
                <input type="range" min="40" max="100" value="70" />
              </label>
              <button class="btn primary full" type="button" onclick="triggerMarketplaceFilter()">Apply Filters</button>
            </aside>

            <div>
              <div class="toolbar">
                <div class="segmented" aria-label="Listing type">
                  <button class="active" type="button">Products</button>
                </div>
                <select aria-label="Sort listings">
                  <option>Newest first</option>
                  <option>Highest readiness</option>
                  <option>Lowest MOQ</option>
                </select>
              </div>
              <div id="productGrid" class="product-grid"></div>
            </div>
          </div>
        </div>
      </section>

      <section id="exporter" class="view">
        <div class="page-shell">
          <div class="section-heading">
            <span class="eyebrow">Exporter Directory</span>
            <h2>Verified Adamawa exporters in one view</h2>
            <p>Browse trusted cooperatives, processors, and trade-ready suppliers by sector, certification, and fulfillment readiness.</p>
          </div>

          <div class="exporter-grid">
            <article class="exporter-card">
              <div class="exporter-card-top">
                <span class="status active">Verified</span>
                <span class="exporter-score">92% readiness</span>
              </div>
              <h3>Ganye Agro Cooperative</h3>
              <p>Sesame seed, soybean, and maize aggregation for bulk export programs.</p>
              <div class="exporter-tags">
                <span>Agriculture</span>
                <span>Phytosanitary</span>
                <span>FOB Lagos</span>
              </div>
              <dl class="exporter-meta">
                <div><dt>Location</dt><dd>Ganye</dd></div>
                <div><dt>MOQ</dt><dd>10 MT</dd></div>
                <div><dt>Markets</dt><dd>UAE, India</dd></div>
              </dl>
              <div class="exporter-actions">
                <a class="btn primary" href="#marketplace">View Products</a>
                <a class="btn secondary" href="{{ route('register') }}">Contact</a>
              </div>
            </article>

            <article class="exporter-card">
              <div class="exporter-card-top">
                <span class="status active">Verified</span>
                <span class="exporter-score">88% readiness</span>
              </div>
              <h3>Mubi Agro Exporters</h3>
              <p>Hibiscus flower, ginger, and dried spice lots prepared for regional and EU buyers.</p>
              <div class="exporter-tags">
                <span>Food Processing</span>
                <span>Organic</span>
                <span>CIF support</span>
              </div>
              <dl class="exporter-meta">
                <div><dt>Location</dt><dd>Mubi</dd></div>
                <div><dt>MOQ</dt><dd>6 MT</dd></div>
                <div><dt>Markets</dt><dd>Netherlands, Egypt</dd></div>
              </dl>
              <div class="exporter-actions">
                <a class="btn primary" href="#marketplace">View Products</a>
                <a class="btn secondary" href="{{ route('register') }}">Contact</a>
              </div>
            </article>
          </div>
        </div>
      </section>
@endsection
@section('scripts')
    <script>
      function triggerMarketplaceFilter() {
        if (typeof window.renderProducts === 'function') {
          window.renderProducts();
        }
      }
      
      document.addEventListener('DOMContentLoaded', () => {
        const hsInput = document.getElementById('heroSearchInput');
        const hcSelect = document.getElementById('heroCategorySelect');
        const hsBtn = document.getElementById('heroSearchBtn');
        
        if (hsBtn) {
          hsBtn.addEventListener('click', () => {
            const query = hsInput.value;
            const category = hcSelect.value;
            
            document.getElementById('productSearch').value = query;
            if (category !== 'All sectors') {
              document.getElementById('categoryFilter').value = category;
            } else {
              document.getElementById('categoryFilter').value = 'all';
            }
            
            window.location.hash = '#marketplace';
            triggerMarketplaceFilter();
          });
        }
      });
    </script>
@endsection

