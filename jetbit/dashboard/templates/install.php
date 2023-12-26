<?php
/**
 * Grafema install wizard.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/install.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-400">
	<div class="mb-8 df jcc">
		<svg width="160" height="42" viewBox="0 0 160 42" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M27.8 37h-9.9v-4.3a9.6 9.6 0 0 1-2.9 3.5c-1.2 1-3 1.4-5 1.4a9 9 0 0 1-7.3-3.2A13 13 0 0 1 0 25.8c0-3.5.9-6.5 2.6-9 1.7-2.4 4.4-3.6 7.9-3.6 1.9 0 3.4.3 4.6 1.1a8 8 0 0 1 2.8 3V6.3h-4.6V1.3h11.4V32h3v5Zm-9.9-11.8v-.5c0-2-.5-3.5-1.5-4.6a5 5 0 0 0-3.8-1.6c-1.5 0-2.8.5-4 1.6C7.6 21.2 7 23 7 25.4c0 2.2.5 4 1.5 5.1 1 1.2 2.3 1.8 3.7 1.8 1.8 0 3.2-.7 4.2-2.2 1-1.5 1.5-3.1 1.5-4.9ZM33 9.5v-7h6.8v7H33ZM43.2 37h-13v-5h3.2V18.6H30v-5H40V32h3.1v5ZM74.3 37h-13v-5h3.2V24c0-2-.4-3.5-1-4.3-.7-1-1.7-1.3-3-1.3-1.6 0-2.9.5-3.7 1.5-.9 1-1.4 2.2-1.4 3.8V32h3.1v5h-13v-5h3.2V18.7h-3.4v-5h10.1v3.5c1.6-2.7 4-4 7.6-4 2.2 0 4.1.6 5.7 2 1.7 1.3 2.5 3.3 2.5 6V32h3.1v5ZM136.6 37h-10v-3.5c-1.6 2.7-4.1 4-7.6 4-2.2 0-4.1-.6-5.8-2-1.6-1.2-2.4-3.3-2.4-6V18.6h-3.1v-5h9.8v13.1c0 2 .3 3.5 1 4.4.7.8 1.7 1.3 3 1.3 1.6 0 2.8-.6 3.7-1.6.9-1 1.3-2.4 1.3-4v-8.2h-3.7v-5h10.5V32h3.3v5ZM160 30c0 2.5-1 4.5-3.1 5.7-2 1.3-4.7 2-8 2-1.7 0-3.5-.3-5.2-.6-1.7-.4-3.3-1-4.9-1.8l1-6.5 5.2.5v3a9 9 0 0 0 3.5.4c1.3 0 2.4-.2 3.3-.6 1-.4 1.4-1 1.4-1.9 0-1-.7-1.6-2.1-2l-5-.9c-1.8-.3-3.5-1-4.9-2-1.4-.9-2.1-2.5-2.1-4.9 0-2.8 1.1-4.8 3.3-5.7a17 17 0 0 1 7-1.5c3.1 0 6 .7 8.8 2l.6 6-5.3.6-.4-3.2c-1.1-.4-2.3-.6-3.7-.6-.8 0-1.6.2-2.2.6-.6.3-.9.9-.9 1.6 0 1 .7 1.7 2 2 1.4.4 3 .7 4.8 1 1.9.3 3.5 1 4.8 2 1.4.9 2.1 2.5 2.1 4.7Z" fill="#000"/>
			<path d="m89 39.7-7.7-2.5 4.4-11.6v-6.7l-5-11.8 8-2.7 3.1 12.3 9.8-8.2 4.7 6.6L96 22l10.7 6.7-4.7 6.6-9.8-8-3 12.5Z" fill="red"/>
			<path d="m85.7 25.6-12.5 1-.2-8.4 12.8.7-.1 6.7Z" fill="#000"/>
		</svg>
	</div>
	<form class="card card-border" method="post" @submit.prevent="$ajax('install-website')" x-data="{name: '', description: '', dbname: '', username: '', db_password: '', hostname: 'localhost', prefix: 'grafema_', login: '', email: '', password: ''}">
		<!-- step 1 -->
		<div x-wizard:step="name.trim()">
			<div class="t-center p-8">
				<h2>Welcome to Grafema!</h2>
				<p class="t-muted">This is installation wizard. Before start, you need to set some settings. Please fill the information about your website.</p>
			</div>
			<div class="card-hr">Website data</div>
			<div class="dg g-6 p-8 pb-0">
				<div class="dg g-1">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Site name</span>
						<input type="text" x-model="name" name="name" placeholder="Example: My Blog" x-autocomplete required>
					</label>
				</div>
				<div class="dg g-1">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Site description</span>
						<input type="text" x-model="description" name="description" placeholder="Example: Just Another Website" x-autocomplete>
					</label>
					<div class="fs-13 t-muted"><?php I18n::e( 'Don\'t worry, you can always change these settings later.' ); ?></div>
				</div>
			</div>
		</div>

		<!-- step 2 -->
		<div x-wizard:step="dbname.trim() && username.trim() && db_password.trim() && hostname.trim() && prefix.trim()" x-cloak>
			<div class="t-center p-8">
				<h2>Step 1: Database</h2>
				<p class="t-muted">Enter information about connecting to the database here. If you are not sure about it, contact your hosting provider.</p>
			</div>
			<div class="card-hr">Database credits</div>
			<div class="dg gtc-2 g-6 p-8 pb-0">
				<div class="dg g-1 ga-2">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Database name</span>
						<input type="text" x-model="dbname" name="dbname" placeholder="database_name" x-autocomplete required>
					</label>
				</div>
				<div class="dg g-1 ga-2">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Database username</span>
						<input type="text" x-model="username" name="username" placeholder="user_name" x-autocomplete required>
					</label>
				</div>
				<div class="dg g-1 ga-2">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Database password</span>
						<input type="text" x-model="db_password" name="db_password" placeholder="Password" x-autocomplete required>
					</label>
				</div>
				<div class="dg g-1 sm:ga-2">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Hostname</span>
						<input type="text" x-model="hostname" name="hostname" placeholder="Hostname" x-autocomplete required>
					</label>
				</div>
				<div class="dg g-1 sm:ga-2">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Prefix</span>
						<input type="text" x-model="prefix" name="prefix" placeholder="Prefix" x-autocomplete required>
					</label>
					<div class="fs-13 t-muted"><?php I18n::e( 'Don\'t worry, you can always change these settings later.' ); ?></div>
				</div>
			</div>
		</div>

		<!-- step 3 -->
		<div x-wizard:step="login.trim() && email.trim() && password.trim()" x-cloak>
			<div class="t-center p-8">
				<h2>Step 2: Create account</h2>
				<p class="t-muted">The last step. Create a site administrator.</p>
			</div>
			<div class="card-hr">User credits</div>
			<div class="dg g-6 p-8 pb-0">
				<div class="dg g-1">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">User login</span>
						<input type="text" x-model="login" name="login" placeholder="Enter login" required>
					</label>
					<div class="fs-13 t-muted"><?php I18n::e( 'Can use only alphanumeric characters, underscores, hyphens and @ symbol.' ); ?></div>
				</div>
				<div class="dg g-1">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Email address</span>
						<input type="email" x-model="email" name="email" placeholder="Enter email" required>
					</label>
					<div class="fs-13 t-muted"><?php I18n::e( 'Double-check your email address before continuing.' ); ?></div>
				</div>
				<div class="dg g-1">
					<label class="dg g-1">
						<span class="df aic jcsb fw-600">Password</span>
						<input type="password" x-model="password" name="password" placeholder="Password" x-autocomplete required>
					</label>
					<div class="fs-13 t-muted"><?php I18n::e( 'You will need this password to sign&nbsp;in. Please store it in a secure location.' ); ?></div>
				</div>
			</div>
		</div>

		<!-- buttons -->
		<div class="p-8 df jcsb g-2">
			<button type="button" class="btn btn--outline" :disabled="$wizard.cannotGoBack()" @click="$wizard.goBack()" disabled>
				Back
			</button>
			<button type="button" class="btn btn--primary" :disabled="$wizard.cannotGoNext()" x-show="$wizard.isNotLast()" @click="$wizard.goNext()" disabled>
				Continue
			</button>
			<button type="submit" class="btn btn--primary" :disabled="$wizard.isUncompleted()" x-show="$wizard.isLast()" disabled>
				Install Grafema
			</button>
		</div>
	</form>
</div>
