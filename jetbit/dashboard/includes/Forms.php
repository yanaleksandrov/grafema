<?php
use Grafema\I18n;
use Grafema\Url;
use Grafema\Option;

/**
 * Set dashboard constants.
 */
final class Forms
{
	use \Grafema\Patterns\Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		/*
		 * Form for build custom fields
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'tools-custom-fields',
			[
				'class'           => 'builder',
				'x-data'          => 'builder',
				'@submit.prevent' => 'submit()',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name' => 'builder',
							'type' => 'builder',
						],
					]
				);
			}
		);

		/*
		 * Sign In form
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'items/actions',
			[
				'class'           => 'dg g-4',
				'x-data'          => '{email: ""}',
				'@submit.prevent' => '$ajax("items/options")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'        => 'action',
							'type'        => 'select',
							'label'       => I18n::__( 'Number of items per page' ),
							'label_class' => 'df aic fs-12 t-muted',
							'value'       => '',
							'reset'       => false,
							'attributes'  => [
								'x-select' => '',
							],
							'options' => [
								'edit'  => I18n::__( 'Edit' ),
								'trash' => I18n::__( 'Move to trash' ),
							],
						],
						[
							'name'       => 'apply',
							'type'       => 'submit',
							'label'      => I18n::__( 'Apply' ),
							'attributes' => [
								'class' => 'btn btn--primary',
							],
						],
					]
				);
			}
		);

		/*
		 * Sign In form
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'items/options',
			[
				'class'           => 'dg g-4',
				'x-data'          => '{email: ""}',
				'@submit.prevent' => '$ajax("items/options")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'        => 'perpage',
							'type'        => 'select',
							'label'       => I18n::__( 'Number of items per page' ),
							'label_class' => 'df aic fs-12 t-muted',
							'value'       => '',
							'reset'       => false,
							'attributes'  => [
								'x-select' => '',
							],
							'options' => [
								'25'  => 25,
								'50'  => 50,
								'100' => 100,
								'250' => 250,
								'500' => 500,
							],
						],
						[
							'name'        => 'remember',
							'type'        => 'checkbox',
							'label'       => I18n::__( 'Columns' ),
							'label_class' => 'df aic fs-12 t-muted',
							'reset'       => false,
							'instruction' => '',
							'attributes'  => [
								'value' => true,
							],
							'options' => [
								'author'     => I18n::__( 'Author' ),
								'categories' => I18n::__( 'Categories' ),
								'date'       => I18n::__( 'Date' ),
							],
						],
						[
							'name'       => 'apply',
							'type'       => 'submit',
							'label'      => I18n::__( 'Apply' ),
							'attributes' => [
								'class' => 'btn btn--primary',
							],
						],
					]
				);
			}
		);

		/*
		 * Sign In form
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'user/sign-in',
			[
				'class'           => 'card card-border g-6 p-8',
				'x-data'          => '{close: false}',
				'@submit.prevent' => '$ajax("user/sign-in")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'type'        => 'header',
							'label'       => I18n::__( 'Welcome to Grafema' ),
							'name'        => 'title',
							'class'       => '',
							'instruction' => I18n::__( 'Login to you account and enjoy exclusive features and many more' ),
						],
						[
							'type'        => 'text',
							'label'       => I18n::__( 'Login or email' ),
							'name'        => 'login',
							'value'       => '',
							'placeholder' => I18n::__( 'Enter login or email' ),
							'class'       => '',
							'reset'       => 0,
							'required'    => 1,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => '',
							'attributes'  => [],
							'conditions'  => [],
						],
						[
							'type'        => 'password',
							'label'       => I18n::__( 'Password' ),
							'name'        => 'password',
							'value'       => '',
							'placeholder' => I18n::__( 'Password' ),
							'class'       => '',
							'reset'       => 0,
							'required'    => 1,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => sprintf( I18n::__( 'Forgot your password? You can %sreset it here%s' ), '<a href="/dashboard/reset-password">', '</a>' ),
							'attributes'  => [],
							'conditions'  => [],
							'switcher'    => 1,
							'generator'   => 0,
							'indicator'   => 0,
							'characters'  => [],
						],
						[
							'name'        => 'remember',
							'type'        => 'checkbox',
							'label'       => '',
							'label_class' => '',
							'reset'       => false,
							'instruction' => '',
							'attributes'  => [
								'value' => true,
							],
							'options' => [
								'remember' => I18n::__( 'Remember me on this device' ),
							],
						],
						[
							'name'       => 'sign-in',
							'type'       => 'submit',
							'label'      => I18n::__( 'Sign In' ),
							'attributes' => [
								'class'     => 'btn btn--primary',
								'disabled'  => '',
								'@load'     => 'console.log(login, password)',
								':disabled' => '!login.trim() || !password.trim()',
							],
						],
					]
				);
			}
		);

		/*
		 * Sign In form
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'sign/up',
			[
				'class'           => 'card card-border g-6 p-8',
				'x-data'          => '',
				'@submit.prevent' => "\$ajax('sign/up')",
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'type'        => 'header',
							'label'       => I18n::__( 'Create new account' ),
							'name'        => 'title',
							'class'       => '',
							'instruction' => I18n::__( 'After creating an account, more platform features will be available to you' ),
						],
						[
							'type'        => 'text',
							'label'       => I18n::__( 'User Login' ),
							'name'        => 'login',
							'value'       => '',
							'placeholder' => I18n::__( 'Enter user login' ),
							'class'       => '',
							'reset'       => 0,
							'required'    => 1,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => '',
							'attributes'  => [
								'x-autocomplete' => '',
							],
							'conditions' => [],
						],
						[
							'type'        => 'email',
							'label'       => I18n::__( 'User Email' ),
							'name'        => 'email',
							'value'       => '',
							'placeholder' => I18n::__( 'Enter user email' ),
							'class'       => '',
							'reset'       => 0,
							'required'    => 1,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => I18n::__( 'Notifications will be sent to this email' ),
							'attributes'  => [
								'x-autocomplete' => '',
							],
							'conditions' => [],
						],
						[
							'type'        => 'password',
							'label'       => I18n::__( 'Password' ),
							'name'        => 'password',
							'value'       => '',
							'placeholder' => I18n::__( 'Password' ),
							'class'       => '',
							'required'    => 1,
							'tooltip'     => '',
							'instruction' => '',
							'attributes'  => [
								'x-autocomplete' => '',
							],
							'conditions' => [],
							'switcher'   => 1,
							'generator'  => 1,
							'indicator'  => 0,
							'characters' => [
								'lowercase' => 2,
								'uppercase' => 2,
								'special'   => 2,
								'length'    => 12,
								'digit'     => 2,
							],
						],
						[
							'name'       => 'signup',
							'type'       => 'submit',
							'label'      => I18n::__( 'Sign Up' ),
							'attributes' => [
								'class'     => 'btn btn--primary',
								'disabled'  => '',
								':disabled' => '![login, email, password].every(i => i.trim())',
							],
						],
					]
				);
			}
		);

		/*
		 * Sign In form
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'reset/password',
			[
				'class'           => 'card card-border g-6 p-8',
				'x-data'          => '',
				'@submit.prevent' => '$ajax("reset/password")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'        => 'title',
							'type'        => 'header',
							'label'       => I18n::__( 'Reset password' ),
							'class'       => 't-center',
							'instruction' => I18n::__( 'Enter the email address that you used to register. We will send you an email that will allow you to reset your password.' ),
						],
						[
							'name'       => 'email',
							'type'       => 'email',
							'label'      => I18n::__( 'Your email' ),
							'attributes' => [
								'required'       => true,
								'placeholder'    => I18n::__( 'Enter your email address' ),
								'x-autocomplete' => '',
								'x-model'        => 'email',
							],
						],
						[
							'name'       => 'sign-in',
							'type'       => 'submit',
							'label'      => I18n::__( 'Send me instructions' ),
							'attributes' => [
								'class'     => 'btn btn--primary',
								'disabled'  => '',
								':disabled' => '!/\S+@\S+\.\S+/.test(email)',
							],
						],
					]
				);
			}
		);

		/*
		 * Form for create new emails
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'grafema-filter-posts',
			[
				'class'    => 'dg g-7 p-8',
				'@change'  => '$ajax("filter/posts")',
				'x-sticky' => '',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'        => 'progress',
							'type'        => 'progress',
							'label'       => I18n::__( 'Storage' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'instruction' => I18n::__( '25% used of 2GB' ),
							'max'         => 100,
							'min'         => 0,
							'value'       => 75,
							'speed'       => 500,
						],
						[
							'name'        => 's',
							'type'        => 'search',
							'label'       => I18n::__( 'Search' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'attributes'  => [
								'placeholder' => I18n::__( 'e.g. image name' ),
							],
						],
						[
							'name'        => 'types[]',
							'type'        => 'checkbox',
							'label'       => I18n::__( 'File types' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'reset'       => true,
							'instruction' => '',
							'attributes'  => [
								'name'  => 'format',
								'value' => true,
							],
							'options' => [
								'svg'    => sprintf( '%s%s', I18n::__( 'SVG' ), '<span class="badge ml-auto bg-sky-lt">56</span>' ),
								'images' => sprintf( '%s%s', I18n::__( 'Images' ), '<span class="badge ml-auto bg-sky-lt">670</span>' ),
								'video'  => sprintf( '%s%s', I18n::__( 'Video' ), '<span class="badge ml-auto bg-sky-lt">35</span>' ),
								'audio'  => sprintf( '%s%s', I18n::__( 'Audio' ), '<span class="badge ml-auto bg-sky-lt">147</span>' ),
								'zip'    => sprintf( '%s%s', I18n::__( 'ZIP' ), '<span class="badge ml-auto bg-sky-lt">74</span>' ),
							],
						],
						[
							'name'        => 'authors',
							'type'        => 'select',
							'label'       => I18n::__( 'Author' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'value'       => '',
							'attributes'  => [
								'x-select' => '',
								'@change'  => '() => {console.log(authors)}',
							],
							'options' => [
								''                => I18n::__( 'Select an author' ),
								'user-registered' => I18n::__( 'New user registered' ),
							],
						],
					]
				);
			}
		);

		/*
		 * Form for create new emails
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'grafema-filter-plugins',
			[
				'class'    => 'dg g-7 p-8',
				'@change'  => '$ajax("filter/plugins")',
				'x-sticky' => '',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'        => 's',
							'type'        => 'search',
							'label'       => I18n::__( 'Search extensions' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'attributes'  => [
								'placeholder' => I18n::__( 'e.g. eCommerce' ),
							],
						],
						[
							'name'        => 'categories[]',
							'type'        => 'checkbox',
							'label'       => I18n::__( 'Categories' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'reset'       => true,
							'instruction' => '',
							'attributes'  => [
								'name'  => 'categories',
								'value' => true,
							],
							'options' => [
								'commerce'  => sprintf( '%s%s', I18n::__( 'Commerce' ), '<span class="badge ml-auto bg-sky-lt">56</span>' ),
								'analytics' => sprintf( '%s%s', I18n::__( 'Analytics' ), '<span class="badge ml-auto bg-sky-lt">670</span>' ),
								'security'  => sprintf( '%s%s', I18n::__( 'Security' ), '<span class="badge ml-auto bg-sky-lt">35</span>' ),
								'seo'       => sprintf( '%s%s', I18n::__( 'SEO' ), '<span class="badge ml-auto bg-sky-lt">147</span>' ),
								'content'   => sprintf( '%s%s', I18n::__( 'Content' ), '<span class="badge ml-auto bg-sky-lt">74</span>' ),
							],
						],
						[
							'name'        => 'rating[]',
							'type'        => 'checkbox',
							'label'       => I18n::__( 'Rating' ),
							'label_class' => 'df aic fs-12 t-muted mb-1',
							'reset'       => true,
							'instruction' => '',
							'attributes'  => [
								'name'  => 'rating',
								'value' => true,
							],
							'options' => [
								'commerce'  => sprintf( '%s%s', I18n::__( 'Show all' ), '<span class="badge ml-auto bg-sky-lt">56</span>' ),
								'analytics' => sprintf( '%s%s', I18n::__( '1 star and higher' ), '<span class="badge ml-auto bg-sky-lt">670</span>' ),
								'security'  => sprintf( '%s%s', I18n::__( '2 stars and higher' ), '<span class="badge ml-auto bg-sky-lt">35</span>' ),
								'seo'       => sprintf( '%s%s', I18n::__( '3 stars and higher' ), '<span class="badge ml-auto bg-sky-lt">147</span>' ),
								'content'   => sprintf( '%s%s', I18n::__( '4 stars and higher' ), '<span class="badge ml-auto bg-sky-lt">74</span>' ),
							],
						],
					]
				);
			}
		);

		/*
		 * Form for create new emails
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'jb-add-email',
			[
				'class'          => 'dg g-6 p-6',
				'@submit.window' => '$ajax("import-email")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'       => 'title',
							'type'       => 'text',
							'label'      => I18n::__( 'Email heading' ),
							'attributes' => [
								'required'    => true,
								'placeholder' => I18n::__( 'Email heading' ),
							],
						],
						[
							'name'       => 'event',
							'type'       => 'select',
							'label'      => I18n::__( 'Event' ),
							'value'      => '',
							'attributes' => [
								'x-select' => '',
								'required' => true,
							],
							'options' => [
								''                => I18n::__( 'Select an event' ),
								'user-registered' => I18n::__( 'New user registered' ),
							],
						],
						[
							'name'       => 'subtitle',
							'type'       => 'text',
							'label'      => I18n::__( 'Subtitle' ),
							'attributes' => [
								'required'    => true,
								'placeholder' => I18n::__( 'Subtitle' ),
							],
						],
						[
							'name'        => 'content',
							'type'        => 'textarea',
							'label'       => I18n::__( 'Content' ),
							'instruction' => I18n::__( 'Enter recipients for this email. Each recipient email from a new line.' ),
							'attributes'  => [
								'rows'        => 5,
								'required'    => true,
								'placeholder' => I18n::__( 'N/A' ),
							],
						],
						[
							'name'        => 'recipients',
							'type'        => 'textarea',
							'label'       => I18n::__( 'Recipient(s)' ),
							'instruction' => I18n::__( 'Enter recipients for this email. Each recipient email from a new line.' ),
							'attributes'  => [
								'required'    => true,
								'placeholder' => I18n::__( 'N/A' ),
							],
						],
					]
				);
			}
		);

		/*
		 * Form for build new posts
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'jb-add-post',
			[
				'class'           => 'dg g-8',
				'x-data'          => '{post: []}',
				'@submit.prevent' => 'submit()',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'   => 'permalinks',
							'type'   => 'group',
							'label'  => I18n::__( 'Permalink' ),
							'fields' => [
								[
									'name'       => 'permalink',
									'type'       => 'text',
									'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
									'class'      => 'dg g-1 ga-2',
									'attributes' => [
										'required' => true,
									],
								],
							],
						],
						[
							'name'   => 'thumbnail',
							'type'   => 'group',
							'label'  => I18n::__( 'Thumbnail' ),
							'fields' => [
								[
									'name'        => 'owners',
									'type'        => 'text',
									'label'       => I18n::__( 'Owner email address' ),
									'instruction' => I18n::__( 'This address is used for admin purposes. If you change this, an email will be sent to your new address to confirm it. The new address will not become active until confirmed.' ),
									'class'       => 'dg g-1 ga-2',
									'attributes'  => [
										'x-media' => '',
									],
								],
							],
						],
						[
							'name'   => 'summary',
							'type'   => 'group',
							'label'  => I18n::__( 'Summary' ),
							'fields' => [
								[
									'name'       => 'visibility',
									'type'       => 'select',
									'label'      => I18n::__( 'Visibility' ),
									'value'      => 'public',
									'attributes' => [
										'x-select' => '{"showSearch":1}',
									],
									'options' => [
										'public'  => I18n::__( 'Public' ),
										'pending' => I18n::__( 'Password protected' ),
										'private' => I18n::__( 'Private' ),
									],
								],
								[
									'name'       => 'status',
									'type'       => 'select',
									'label'      => I18n::__( 'Status' ),
									'value'      => 'publish',
									'attributes' => [
										'x-select' => '{"showSearch":1}',
									],
									'options' => [
										'publish' => I18n::__( 'Publish' ),
										'pending' => I18n::__( 'Pending' ),
										'draft'   => I18n::__( 'Draft' ),
									],
								],
								[
									'name'        => 'tagline',
									'type'        => 'text',
									'label'       => I18n::__( 'Template' ),
									'instruction' => I18n::__( 'Templates define the way content is displayed when viewing your site' ),
									'attributes'  => [
										'required'    => true,
										'placeholder' => I18n::__( 'e.g. Just Another Grafema Website' ),
									],
								],
								[
									'name'        => 'language',
									'type'        => 'select',
									'label'       => I18n::__( 'Author' ),
									'instruction' => I18n::__( 'Some description' ),
									'value'       => 'us',
									'attributes'  => [
										'x-select' => '{"showSearch":1}',
									],
									'options' => [
										'us' => [
											'image'   => 'assets/images/flags/us.svg',
											'content' => I18n::__( 'English - english' ),
										],
										'ru' => [
											'image'   => 'assets/images/flags/ru.svg',
											'content' => I18n::__( 'Russian - русский' ),
										],
										'he' => [
											'image'   => 'assets/images/flags/il.svg',
											'content' => I18n::__( 'עִבְרִית - Hebrew' ),
										],
									],
								],
							],
						],
						[
							'name'   => 'discussion',
							'type'   => 'group',
							'label'  => I18n::__( 'Discussion' ),
							'fields' => [
								[
									'name'       => 'discussion',
									'type'       => 'select',
									'value'      => 'public',
									'attributes' => [
										'x-select' => '',
									],
									'options' => [
										'open'        => I18n::__( 'Open' ),
										'close'       => I18n::__( 'Close' ),
										'temporarily' => I18n::__( 'Temporarily' ),
									],
								],
							],
						],
					]
				);
			}
		);

		/*
		 * Profile page
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'jb-profile',
			[
				'class'  => 'tab',
				'x-data' => "{tab:'profile'}",
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'type'          => 'tab',
							'label'         => I18n::__( 'Profile' ),
							'name'          => 'profile',
							'caption'       => '',
							'description'   => '',
							'icon'          => 'ph ph-user',
							'class_button'  => 'ml-8',
							'class_content' => 'p-8',
							'fields'        => [
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Email (required)' ),
									'fields' => [
										[
											'name'        => 'email',
											'type'        => 'email',
											'instruction' => I18n::__( 'Is not displayed anywhere. It is used to work with the account and system notifications' ),
											'before'      => '<i class="ph ph-at"></i>',
											'attributes'  => [
												'required'    => true,
												'placeholder' => I18n::__( 'e.g. user@gmail.com' ),
											],
										],
									],
								],
								[
									'name'    => 'name',
									'type'    => 'group',
									'label'   => I18n::__( 'Name' ),
									'columns' => 2,
									'fields'  => [
										[
											'name'        => 'firstname',
											'type'        => 'text',
											'label'       => I18n::__( 'First name' ),
											'instruction' => I18n::__( 'Your name may appear around website where you contribute or are mentioned. You can remove it at any time.' ),
											'attributes'  => [
												'placeholder' => I18n::__( 'First name' ),
											],
										],
										[
											'name'       => 'lastname',
											'type'       => 'text',
											'label'      => I18n::__( 'Last name' ),
											'attributes' => [
												'placeholder' => I18n::__( 'Last name' ),
											],
										],
										[
											'name'        => 'display',
											'type'        => 'text',
											'label'       => I18n::__( 'Display name publicly as' ),
											'instruction' => I18n::__( 'Your name may appear where you comment or write new articles' ),
											'attributes'  => [
												'required'    => true,
												'placeholder' => I18n::__( 'Display name' ),
											],
										],
										[
											'name'        => 'username',
											'type'        => 'text',
											'label'       => I18n::__( 'Username' ),
											'instruction' => I18n::__( 'Сannot be changed' ),
											'attributes'  => [
												'required'    => true,
												'readonly'    => true,
												'placeholder' => I18n::__( 'Username' ),
											],
										],
									],
								],
								[
									'name'   => 'activity',
									'type'   => 'group',
									'label'  => I18n::__( 'Contributions & Activity' ),
									'fields' => [
										[
											'name'        => 'email',
											'type'        => 'email',
											'instruction' => I18n::__( 'Is not displayed anywhere. It is used to work with the account and system notifications' ),
											'before'      => '<i class="ph ph-at"></i>',
											'attributes'  => [
												'required'    => true,
												'placeholder' => I18n::__( 'e.g. user@gmail.com' ),
											],
										],
									],
								],
							],
						],
						[
							'name'          => 'appearance',
							'type'          => 'tab',
							'label'         => I18n::__( 'Appearance' ),
							'description'   => '',
							'icon'          => 'ph ph-paint-brush-broad',
							'class_button'  => '',
							'class_content' => 'p-8',
							'fields'        => [
								[
									'name'    => 'theme',
									'type'    => 'group',
									'label'   => I18n::__( 'Theme preferences' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'        => 'format',
											'type'        => 'radio',
											'variation'   => 'image',
											'instruction' => I18n::__( 'Choose how dashboard looks to you. Select a single theme, or sync with your system and automatically switch between day and night themes.' ),
											'value'       => 'light',
											'width'       => 180,
											'options'     => [
												'light' => [
													'image' => Url::site( 'dashboard/assets/images/dashboard-light.svg' ),
													'title' => I18n::__( 'Light mode' ),
												],
												'dark' => [
													'image' => Url::site( 'dashboard/assets/images/dashboard-dark.svg' ),
													'title' => I18n::__( 'Dark mode' ),
												],
												'system' => [
													'image' => Url::site( 'dashboard/assets/images/dashboard-system.svg' ),
													'title' => I18n::__( 'System' ),
												],
											],
										],
									],
								],
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Toolbar' ),
									'fields' => [
										[
											'name'       => 'toolbar',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'Show Toolbar when viewing site' ),
											'attributes' => [
												'name'  => 'format',
												'value' => true,
											],
										],
									],
								],
								[
									'name'   => 'language',
									'type'   => 'group',
									'label'  => I18n::__( 'Language' ),
									'fields' => [
										[
											'name'        => 'language',
											'type'        => 'select',
											'instruction' => I18n::__( 'Language for your dashboard panel' ),
											'value'       => 'us',
											'attributes'  => [
												'x-select' => '{"showSearch":1}',
											],
											'options' => [
												'us' => [
													'image'   => 'assets/images/flags/us.svg',
													'content' => I18n::__( 'English - english' ),
												],
												'ru' => [
													'image'   => 'assets/images/flags/ru.svg',
													'content' => I18n::__( 'Russian - русский' ),
												],
												'he' => [
													'image'   => 'assets/images/flags/il.svg',
													'content' => I18n::__( 'עִבְרִית - Hebrew' ),
												],
											],
										],
									],
								],
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Toolbar' ),
									'fields' => [],
								],
							],
						],
						[
							'name'          => 'applications',
							'type'          => 'tab',
							'label'         => I18n::__( 'API keys' ),
							'description'   => '',
							'icon'          => 'ph ph-key',
							'class_button'  => '',
							'class_content' => 'p-8',
							'fields'        => [
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Authentication keys' ),
									'fields' => [
										[
											'name'     => 'title',
											'type'     => 'custom',
											'callback' => function () {
												ob_start();
												?>
												<div class="dg g-2 ga-4">
													<p><?php I18n::e( 'Application passwords allow authentication via non-interactive systems, such as REST API, without providing your actual password. Application passwords can be easily revoked. They cannot be used for traditional logins to your website.' ); ?></p>
													<div class="p-4 df fdr g-4 card card-border">
														<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
															<path d="M160 18a78 78 0 0 0-73.8 103.3l-58.4 58.5A6 6 0 0 0 26 184v40a6 6 0 0 0 6 6h40a6 6 0 0 0 6-6v-18h18a6 6 0 0 0 6-6v-18h18a6 6 0 0 0 4.2-1.8l10.5-10.4A78 78 0 1 0 160 18Zm0 144a65.6 65.6 0 0 1-24.4-4.7 6 6 0 0 0-6.7 1.3L117.5 170H96a6 6 0 0 0-6 6v18H72a6 6 0 0 0-6 6v18H38v-31.5L97.4 127a6 6 0 0 0 1.3-6.7A66 66 0 1 1 160 162Zm30-86a10 10 0 1 1-10-10 10 10 0 0 1 10 10Z"/>
														</svg>
														<div class="dg g-1">
															<h6 class="fs-15">Amplication</h6>
															<code class="fs-12 bg-herbal-lt"><span class="badge badge--sm bg-herbal-lt">Active</span> SHA256:Ai2xqyVBORX9PJJigJxfrdzXfKPajJHZMYw3+dOo+nw</code>
															<div class="fs-12 t-muted lh-xs">Added on Nov 15, 2022</div>
														</div>
														<div class="ml-auto">
															<button class="btn btn--outline" type="button">Delete</button>
														</div>
													</div>
												</div>
												<?php
												return ob_get_clean();
											},
										],
									],
								],
							],
						],
						[
							'name'          => 'sessions',
							'type'          => 'tab',
							'label'         => I18n::__( 'Sessions' ),
							'caption'       => '',
							'icon'          => 'ph ph-broadcast',
							'class_button'  => '',
							'class_content' => 'p-8',
							'fields'        => [
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Web sessions' ),
									'fields' => [
										[
											'name'     => 'title',
											'type'     => 'custom',
											'callback' => function () {
												ob_start();
												?>
												<div class="dg g-2 ga-4">
													<p>This is a list of devices that have logged into your account. Revoke any sessions that you do not recognize.</p>
													<div class="p-4 df fdr g-4 card card-border">
														<div class="avatar">
															<i class="badge"></i>
															<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
																<path d="M224 74h-18V64a22 22 0 0 0-22-22H40a22 22 0 0 0-22 22v96a22 22 0 0 0 22 22h114v10a22 22 0 0 0 22 22h48a22 22 0 0 0 22-22V96a22 22 0 0 0-22-22ZM40 170a10 10 0 0 1-10-10V64a10 10 0 0 1 10-10h144a10 10 0 0 1 10 10v10h-18a22 22 0 0 0-22 22v74Zm194 22a10 10 0 0 1-10 10h-48a10 10 0 0 1-10-10V96a10 10 0 0 1 10-10h48a10 10 0 0 1 10 10Zm-100 16a6 6 0 0 1-6 6H88a6 6 0 0 1 0-12h40a6 6 0 0 1 6 6Zm80-96a6 6 0 0 1-6 6h-16a6 6 0 0 1 0-12h16a6 6 0 0 1 6 6Z"/>
															</svg>
														</div>
														<div class="dg g-1">
															<h6 class="fs-15">Turkey, Antalya 46.197.118.72</h6>
															<code class="fs-12">Microsoft Edge on Windows</code>
															<div class="fs-12 t-muted lh-xs">Your current session</div>
														</div>
														<div class="ml-auto">
															<button class="btn btn--outline" type="button">Delete</button>
														</div>
													</div>
													<div class="p-4 df fdr g-4 card card-border">
														<div class="avatar">
															<i class="badge bg-herbal"></i>
															<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
																<path d="M176 18H80a22 22 0 0 0-22 22v176a22 22 0 0 0 22 22h96a22 22 0 0 0 22-22V40a22 22 0 0 0-22-22Zm10 198a10 10 0 0 1-10 10H80a10 10 0 0 1-10-10V40a10 10 0 0 1 10-10h96a10 10 0 0 1 10 10ZM138 60a10 10 0 1 1-10-10 10 10 0 0 1 10 10Z"/>
															</svg>
														</div>
														<div class="dg g-1">
															<h6 class="fs-15">Germany, Berlin 26.144.105.72</h6>
															<code class="fs-12">Chromium on Linux</code>
															<div class="fs-12 t-muted lh-xs">Your current session</div>
														</div>
														<div class="ml-auto">
															<button class="btn btn--outline" type="button">Delete</button>
														</div>
													</div>
												</div>
												<?php
												return ob_get_clean();
											},
										],
									],
								],
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Security log' ),
									'fields' => [
										[
											'name'     => 'title',
											'type'     => 'custom',
											'callback' => function () {
												ob_start();
												?>
												<div class="dg g-2 ga-4">
													<p>https://github.com/settings/security-log</p>
													<div class="p-4 df fdr g-4 card card-border">
														<div class="avatar">
															<i class="badge"></i>
															<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
																<path d="M224 74h-18V64a22 22 0 0 0-22-22H40a22 22 0 0 0-22 22v96a22 22 0 0 0 22 22h114v10a22 22 0 0 0 22 22h48a22 22 0 0 0 22-22V96a22 22 0 0 0-22-22ZM40 170a10 10 0 0 1-10-10V64a10 10 0 0 1 10-10h144a10 10 0 0 1 10 10v10h-18a22 22 0 0 0-22 22v74Zm194 22a10 10 0 0 1-10 10h-48a10 10 0 0 1-10-10V96a10 10 0 0 1 10-10h48a10 10 0 0 1 10 10Zm-100 16a6 6 0 0 1-6 6H88a6 6 0 0 1 0-12h40a6 6 0 0 1 6 6Zm80-96a6 6 0 0 1-6 6h-16a6 6 0 0 1 0-12h16a6 6 0 0 1 6 6Z"/>
															</svg>
														</div>
														<div class="dg g-1">
															<h6 class="fs-15">Turkey, Antalya 46.197.118.72</h6>
															<code class="fs-12">Microsoft Edge on Windows</code>
															<div class="fs-12 t-muted lh-xs">Your current session</div>
														</div>
														<div class="ml-auto">
															<button class="btn btn--outline" type="button">Delete</button>
														</div>
													</div>
													<div class="p-4 df fdr g-4 card card-border">
														<div class="avatar">
															<i class="badge bg-herbal"></i>
															<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
																<path d="M176 18H80a22 22 0 0 0-22 22v176a22 22 0 0 0 22 22h96a22 22 0 0 0 22-22V40a22 22 0 0 0-22-22Zm10 198a10 10 0 0 1-10 10H80a10 10 0 0 1-10-10V40a10 10 0 0 1 10-10h96a10 10 0 0 1 10 10ZM138 60a10 10 0 1 1-10-10 10 10 0 0 1 10 10Z"/>
															</svg>
														</div>
														<div class="dg g-1">
															<h6 class="fs-15">Germany, Berlin 26.144.105.72</h6>
															<code class="fs-12">Chromium on Linux</code>
															<div class="fs-12 t-muted lh-xs">Your current session</div>
														</div>
														<div class="ml-auto">
															<button class="btn btn--outline" type="button">Delete</button>
														</div>
													</div>
												</div>
												<?php
												return ob_get_clean();
											},
										],
									],
								],
							],
						],
						[
							'name'          => 'password',
							'type'          => 'tab',
							'label'         => I18n::__( 'Security' ),
							'caption'       => '',
							'icon'          => 'ph ph-password',
							'class_button'  => '',
							'class_content' => 'p-8',
							'fields'        => [
								[
									'name'   => 'theme',
									'type'   => 'group',
									'label'  => I18n::__( 'Change password' ),
									'fields' => [],
								],
							],
						],
					]
				);
			}
		);

		/*
		 * Website settings in dashboard
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'jb-settings',
			[
				'class'   => 'tab tab--vertical',
				'x-data'  => "{tab:'general'}",
				'@change' => '$ajax("save-settings")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'    => 'general',
							'type'    => 'tab',
							'label'   => I18n::__( 'General' ),
							'caption' => I18n::__( 'main settings' ),
							'icon'    => 'ph ph-browsers',
							'fields'  => [
								[
									'name'   => 'website',
									'type'   => 'group',
									'label'  => I18n::__( 'Website' ),
									'fields' => [
										[
											'name'        => 'site[name]',
											'type'        => 'text',
											'label'       => I18n::__( 'Name' ),
											'instruction' => I18n::__( 'A quick snapshot of your website.' ),
											'attributes'  => [
												'value'       => Option::get( 'site.name' ),
												'required'    => true,
												'placeholder' => I18n::__( 'e.g. Google' ),
											],
										],
										[
											'name'        => 'site[tagline]',
											'type'        => 'text',
											'label'       => I18n::__( 'Tagline' ),
											'instruction' => I18n::__( 'In a few words, explain what this site is about.' ),
											'attributes'  => [
												'value'       => Option::get( 'site.tagline' ),
												'required'    => true,
												'placeholder' => I18n::__( 'e.g. Just Another Grafema Website' ),
											],
										],
										[
											'name'        => 'site[language]',
											'type'        => 'select',
											'label'       => I18n::__( 'Language' ),
											'instruction' => I18n::__( 'Some description' ),
											'value'       => Option::get( 'site.language' ),
											'attributes'  => [
												'x-select' => '{"showSearch":1}',
											],
											'options' => [
												'us' => [
													'image'   => 'assets/images/flags/us.svg',
													'content' => I18n::__( 'English - english' ),
												],
												'ru' => [
													'image'   => 'assets/images/flags/ru.svg',
													'content' => I18n::__( 'Russian - русский' ),
												],
												'he' => [
													'image'   => 'assets/images/flags/il.svg',
													'content' => I18n::__( 'עִבְרִית - Hebrew' ),
												],
											],
										],
										[
											'name'        => 'site[url]',
											'type'        => 'text',
											'label'       => I18n::__( 'Site address (URL)' ),
											'instruction' => I18n::__( 'A quick snapshot of your website.' ),
											'attributes'  => [
												'value'       => Option::get( 'site.url' ),
												'required'    => true,
												'placeholder' => I18n::__( 'e.g. Google' ),
											],
										],
									],
								],
								[
									'name'   => 'administrator',
									'type'   => 'group',
									'label'  => I18n::__( 'Administrator' ),
									'fields' => [
										[
											'name'        => 'owner[email]',
											'type'        => 'text',
											'label'       => I18n::__( 'Owner email address!' ),
											'instruction' => I18n::__( 'This address is used for admin purposes. If you change this, an email will be sent to your new address to confirm it. The new address will not become active until confirmed.' ),
											'before'      => '<i class="ph ph-at"></i>',
											'attributes'  => [
												'value'    => Option::get( 'owner.email' ),
												'required' => true,
											],
										],
									],
								],
								[
									'name'   => 'users',
									'type'   => 'group',
									'label'  => I18n::__( 'Users' ),
									'fields' => [
										[
											'name'        => 'users[role]',
											'type'        => 'select',
											'label'       => I18n::__( 'New User Default Role' ),
											'instruction' => I18n::__( 'Some description' ),
											'value'       => Option::get( 'users.role' ),
											'attributes'  => [
												'x-select' => '',
											],
											'options' => [
												'subscriber'    => I18n::__( 'Subscriber' ),
												'contributor'   => I18n::__( 'Contributor' ),
												'author'        => I18n::__( 'Author' ),
												'editor'        => I18n::__( 'Editor' ),
												'administrator' => I18n::__( 'Administrator' ),
											],
										],
										[
											'name'        => 'users[membership]',
											'type'        => 'select',
											'label'       => I18n::__( 'Membership' ),
											'instruction' => I18n::__( 'Some description' ),
											'value'       => Option::get( 'users.membership' ),
											'attributes'  => [
												'x-select' => '',
											],
											'options' => [
												'subscriber' => I18n::__( 'Anyone can register' ),
											],
										],
									],
								],
								[
									'name'   => 'dates',
									'type'   => 'group',
									'label'  => I18n::__( 'Dates & time' ),
									'fields' => [
										[
											'name'     => 'date-format',
											'type'     => 'custom',
											'callback' => function () {
												?>
												<div class="dg g-2">
													<label class="dg">
														<span class="df aic jcsb fw-600"><?php I18n::e( 'Date Format' ); ?></span>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">April 3, 2021</span> <code class="badge bg-dark-lt">F j, Y</code>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">2021-04-03</span> <code class="badge bg-dark-lt">Y-m-d</code>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">04/03/2021</span> <code class="badge bg-dark-lt">m/d/Y</code>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">Custom</span> <input class="mw-80" type="text" name="item">
													</label>
													<div class="fs-13 t-muted"><a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get formats list</a> on php.net</div>
												</div>
												<?php
											},
										],
										[
											'name'     => 'time-format',
											'type'     => 'custom',
											'callback' => function () {
												?>
												<div class="dg g-2">
													<label class="dg">
														<span class="df aic jcsb fw-600"><?php I18n::e( 'Time Format' ); ?></span>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">17:22</span> <code class="badge bg-dark-lt">H:i</code>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">5:22 PM</span> <code class="badge bg-dark-lt">g:i A</code>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">12:50am</span> <code class="badge bg-dark-lt">g:ia</code>
													</label>
													<label class="df aic jcsb">
														<span><input class="mr-2" type="radio" name="item">Custom</span> <input class="mw-80" type="text" name="item">
													</label>
													<div class="fs-13 t-muted"><a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get full time formats list</a> on php.net</div>
												</div>
												<?php
											},
										],
										[
											'name'        => 'week-starts-on',
											'type'        => 'select',
											'label'       => I18n::__( 'Week Starts On' ),
											'instruction' => '<a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get full time formats list</a> on php.net',
											'value'       => Option::get( 'week-starts-on' ),
											'attributes'  => [
												'x-select' => '',
											],
											'options' => [
												'0' => I18n::__( 'Sunday' ),
												'1' => I18n::__( 'Monday' ),
												'2' => I18n::__( 'Tuesday' ),
												'3' => I18n::__( 'Wednesday' ),
												'4' => I18n::__( 'Thursday' ),
												'5' => I18n::__( 'Friday' ),
												'6' => I18n::__( 'Saturday' ),
											],
										],
										[
											'name'        => 'timezone',
											'type'        => 'select',
											'label'       => I18n::__( 'Timezone' ),
											'instruction' => I18n::__( 'Choose either a city in the same timezone as you or a UTC (Coordinated Universal Time) time offset.' ),
											'value'       => Option::get( 'timezone' ),
											'attributes'  => [
												'x-select' => '',
											],
											'options' => [
												'subscriber'    => I18n::__( 'Subscriber' ),
												'contributor'   => I18n::__( 'Contributor' ),
												'author'        => I18n::__( 'Author' ),
												'editor'        => I18n::__( 'Editor' ),
												'administrator' => I18n::__( 'Administrator' ),
											],
										],
									],
								],
							],
						],
						[
							'name'    => 'writing',
							'type'    => 'tab',
							'label'   => I18n::__( 'Writing' ),
							'caption' => I18n::__( 'displaying posts' ),
							'icon'    => 'ph ph-article-ny-times',
							'fields'  => [],
						],
						[
							'name'    => 'reading',
							'type'    => 'tab',
							'label'   => I18n::__( 'Reading' ),
							'caption' => I18n::__( 'displaying posts' ),
							'icon'    => 'ph ph-book-open-text',
							'fields'  => [
								[
									'name'    => 'search_engine',
									'type'    => 'group',
									'label'   => I18n::__( 'Search engine' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'       => 'discourage',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'Discourage search engines from indexing this site' ),
											'attributes' => [
												'value' => Option::get( 'discourage' ),
											],
										],
									],
								],
							],
						],
						[
							'name'    => 'discussions',
							'type'    => 'tab',
							'label'   => I18n::__( 'Discussions' ),
							'caption' => I18n::__( 'comments & chat' ),
							'icon'    => 'ph ph-chats-circle',
							'fields'  => [
								[
									'name'    => 'comments',
									'type'    => 'group',
									'label'   => I18n::__( 'Post comments' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'       => 'comments[default_status]',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'Allow people to submit comments on new posts' ),
											'attributes' => [
												'value' => Option::get( 'comments.default_status' ),
											],
										],
										[
											'name'       => 'comments[registration]',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'Users must be registered and logged in to comment' ),
											'attributes' => [
												'value' => Option::get( 'comments.registration' ),
											],
										],
										[
											'name'       => 'comments[close][after]',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'Automatically close comments on posts older than' ),
											'attributes' => [
												'value' => Option::get( 'comments.close.after' ),
											],
										],
									],
								],
								[
									'name'    => 'comments',
									'type'    => 'group',
									'label'   => I18n::__( 'Email me whenever' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'       => 'comments[notify][posts]',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'Anyone posts a comment' ),
											'attributes' => [
												'value' => Option::get( 'comments.notify.posts' ),
											],
										],
										[
											'name'       => 'comments[notify][moderation]',
											'type'       => 'checkbox',
											'label'      => I18n::__( 'A comment is held for moderation' ),
											'attributes' => [
												'value' => Option::get( 'comments.notify.moderation' ),
											],
										],
									],
								],
							],
						],
						[
							'name'    => 'storage',
							'type'    => 'tab',
							'label'   => I18n::__( 'Storage' ),
							'caption' => I18n::__( 'media options' ),
							'icon'    => 'ph ph-camera',
							'fields'  => [
								[
									'name'    => 'images',
									'type'    => 'group',
									'label'   => I18n::__( 'Images' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'        => 'images[webp]',
											'type'        => 'checkbox',
											'label'       => I18n::__( 'Convert to WebP format' ),
											'instruction' => I18n::__( 'Will provide a higher compression ratio' ),
											'tooltip'     => I18n::__( 'Can lead to loss of detail and image quality, as well as increase the cost of your hosting resources' ),
											'attributes'  => [
												'value' => Option::get( 'images.webp' ),
											],
										],
									],
								],
								[
									'name'    => 'images',
									'type'    => 'group',
									'label'   => I18n::__( 'Limits' ),
									'columns' => 2,
									'fields'  => [
										[
											'name'        => 'limits[owner]',
											'type'        => 'number',
											'label'       => I18n::__( 'For owner' ),
											'after'       => I18n::__( 'MB' ),
											'instruction' => I18n::__( 'The amount of disk space for files.' ),
											'tooltip'     => I18n::__( 'When the threshold is reached, the administrator will receive an email notification.' ),
											'attributes'  => [
												'value' => Option::get( 'limits.owner' ),
											],
										],
										[
											'name'        => 'limits[users]',
											'type'        => 'number',
											'label'       => I18n::__( 'For users' ),
											'after'       => I18n::__( 'MB' ),
											'instruction' => I18n::__( 'The maximum size of files allowed per user.' ),
											'attributes'  => [
												'value' => Option::get( 'limits.users' ),
											],
										],
									],
								],
							],
						],
						[
							'name'        => 'permalinks',
							'type'        => 'tab',
							'label'       => I18n::__( 'Permalinks' ),
							'caption'     => I18n::__( 'URLs structure' ),
							'description' => I18n::__( 'custom URL structures can improve the aesthetics, usability, and forward-compatibility of your links' ),
							'icon'        => 'ph ph-link',
							'fields'      => [
								[
									'name'    => 'dates',
									'type'    => 'group',
									'label'   => I18n::__( 'Pages' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'        => 'permalinks[pages][single]',
											'type'        => 'text',
											'label'       => I18n::__( 'Single page' ),
											'before'      => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
											'tooltip'     => I18n::__( 'ZIP Code must be US or CDN format. You can use an extended ZIP+4 code to determine address more accurately.' ),
											'instruction' => I18n::__( 'Select the permalink structure for your website. Including the %slug% tag makes links easy to understand, and can help your posts rank higher in search engines.' ),
											'attributes'  => [
												'required' => true,
												'value'    => Option::get( 'permalinks.pages.single' ),
											],
										],
										[
											'name'       => 'permalinks[pages][categories]',
											'type'       => 'text',
											'label'      => I18n::__( 'Categories' ),
											'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
											'attributes' => [
												'required' => true,
												'value'    => Option::get( 'permalinks.pages.categories' ),
											],
										],
									],
								],
								[
									'name'    => 'dates',
									'type'    => 'group',
									'label'   => I18n::__( 'Products' ),
									'columns' => 1,
									'fields'  => [
										[
											'name'        => 'permalinks[products][single]',
											'type'        => 'text',
											'label'       => I18n::__( 'Single product' ),
											'before'      => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
											'tooltip'     => I18n::__( 'ZIP Code must be US or CDN format. You can use an extended ZIP+4 code to determine address more accurately.' ),
											'instruction' => I18n::__( 'Select the permalink structure for your website. Including the %slug% tag makes links easy to understand, and can help your posts rank higher in search engines.' ),
											'attributes'  => [
												'required' => true,
												'value'    => Option::get( 'permalinks.products.single' ),
											],
										],
										[
											'name'       => 'permalinks[pages][categories]',
											'type'       => 'text',
											'label'      => I18n::__( 'Products categories' ),
											'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
											'attributes' => [
												'required' => true,
												'value'    => Option::get( 'permalinks.products.categories' ),
											],
										],
										[
											'name'       => 'permalinks[pages][tags]',
											'type'       => 'text',
											'label'      => I18n::__( 'Products tags' ),
											'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
											'attributes' => [
												'required' => true,
												'value'    => Option::get( 'permalinks.products.tags' ),
											],
										],
									],
								],
							],
						],
					]
				);
			}
		);
	}
}
