<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Themes list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/themes.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-filter">
	<form class="dg g-7 p-8" action="." method="get" autocomplete="off">
		<div class="dg">
			<button type="submit" class="btn btn--primary btn--animated" data-txt="Max size: 64Mb"><i class="icon-upload"></i> Upload theme</button>
			<div class="mt-2 t-muted fs-12 t-center">Install or update extension by uploading .zip archive</div>
		</div>
		<div class="dg g-1">
			<div class="fs-12 t-muted">Search theme</div>
			<input type="search" name="search" placeholder="e.g. commerce">
		</div>
		<div class="dg g-1">
			<div class="df aic fs-12 t-muted mb-1">
				<span>Categories</span><span class="ml-auto">Select all</span>
			</div>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>E-commerce</span><span class="badge ml-auto bg-sky-lt">56</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Analytics</span><span class="badge ml-auto bg-sky-lt">1245</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Security</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>SEO</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Content</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
		</div>

		<div class="dg g-1">
			<div class="df aic fs-12 t-muted mb-1">
				<span>Price</span><span class="ml-auto">Select all</span>
			</div>
			<label class="df aic">
				<input type="radio" name="price" checked>
				<span>All prices</span><span class="badge ml-auto bg-sky-lt">700</span>
			</label>
			<label class="df aic">
				<input type="radio" name="price">
				<span>Free</span><span class="badge ml-auto bg-sky-lt">700</span>
			</label>
			<label class="df aic">
				<input type="radio" name="price">
				<span>$1-10</span><span class="badge ml-auto bg-sky-lt">45</span>
			</label>
			<label class="df aic">
				<input type="radio" name="price">
				<span>$11-29</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
			<label class="df aic">
				<input type="radio" name="price">
				<span>$30-59</span><span class="badge ml-auto bg-sky-lt">77</span>
			</label>
			<label class="df aic">
				<input type="radio" name="price">
				<span>$59+</span><span class="badge ml-auto bg-sky-lt">129</span>
			</label>
		</div>
		<div class="dg g-1">
			<div class="df aic fs-12 t-muted mb-1">
				<span>Rating</span><span class="ml-auto">Select all</span>
			</div>
			<label class="df aic">
				<input type="radio" name="rating" checked>
				<span>Show all</span><span class="badge ml-auto bg-sky-lt">700</span>
			</label>
			<label class="df aic">
				<input type="radio" name="rating">
				<span>1 star and higher</span><span class="badge ml-auto bg-sky-lt">45</span>
			</label>
			<label class="df aic">
				<input type="radio" name="rating">
				<span>2 stars and higher</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
			<label class="df aic">
				<input type="radio" name="rating">
				<span>3 stars and higher</span><span class="badge ml-auto bg-sky-lt">77</span>
			</label>
			<label class="df aic">
				<input type="radio" name="rating">
				<span>4 stars and higher</span><span class="badge ml-auto bg-sky-lt">129</span>
			</label>
		</div>
	</form>
</div>

<div class="grafema-main">
	<?php
	View::part(
		'templates/table/header',
		[
			'title' => I18n::__( 'Themes' ),
		]
	);
?>
	<div class="theme">
		<div class="theme__item card card-border card-hover jcsb" data-title="Theme Details">
			<div class="theme__image card-img" style="background-image: url(https://dev.codyshop.ru/wp-content/themes/rgbcode/screenshot.png)"></div>
			<div class="dg g-2 p-5 pt-4 pb-4">
				<div class="fw-600 fs-16 df jcsb aic">Rgbcode <span class="badge bg-herbal-lt">Installed</span></div>
				<div class="t-muted">Multipurpose theme for blog, startup, portfolio, business & e-commerce.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-melon">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v1.0.0</span>
				</div>
			</div>
		</div>
		<div class="theme__item card card-border card-hover jcsb" data-title="Theme Details">
			<div class="theme__image card-img" style="background-image: url(https://dev.codyshop.ru/wp-content/themes/daria/screenshot.jpg)"></div>
			<div class="dg g-2 p-5 pt-4 pb-4">
				<div class="fw-600 fs-16 df jcsb aic">Daria <span class="badge bg-herbal-lt">Installed</span></div>
				<div class="t-muted">Multipurpose theme for blog, startup, portfolio, business & e-commerce.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-melon">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v1.0.0</span>
				</div>
			</div>
		</div>
		<div class="theme__item card card-border card-hover jcsb" data-title="Theme Details">
			<div class="theme__image card-img" style="background-image: url(//ts.w.org/wp-content/themes/twentytwentytwo/screenshot.png)"></div>
			<div class="dg g-2 p-5 pt-4 pb-4">
				<div class="fw-600 fs-16 df jcsb aic">Twenty Twenty-Two <span class="badge bg-herbal-lt">Installed</span></div>
				<div class="t-muted">Multipurpose theme for blog, startup, portfolio, business & e-commerce.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-melon">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v1.0.0</span>
				</div>
			</div>
		</div>
		<div class="theme__item card card-border card-hover jcsb" data-title="Theme Details">
			<div class="theme__image card-img" style="background-image: url(//ts.w.org/wp-content/themes/lemmony/screenshot.png)"></div>
			<div class="dg g-2 p-5 pt-4 pb-4">
				<div class="fw-600 fs-16 df jcsb aic">Lemmony Health <span class="badge bg-herbal-lt">Installed</span></div>
				<div class="t-muted">Lemmony Health is multipurpose eCommerce theme.</div>
				<div class="df jcsb fs-12">
					<span class="t-muted">This theme has not been rated yet.</span>
					<span class="t-muted">v1.0.0</span>
				</div>
			</div>
		</div>
		<div class="theme__item card card-border card-hover jcsb" data-title="Theme Details">
			<div class="theme__image card-img" style="background-image: url(//ts.w.org/wp-content/themes/threadwears/screenshot.png)"></div>
			<div class="dg g-2 p-5 pt-4 pb-4">
				<div class="fw-600 fs-16 df jcsb aic">Threadwears <span class="badge bg-herbal-lt">Installed</span></div>
				<div class="t-muted">Threadwears is a light and elegant free eCommerce Grafema block theme.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-melon">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v1.0.0</span>
				</div>
			</div>
		</div>
	</div>
</div>
