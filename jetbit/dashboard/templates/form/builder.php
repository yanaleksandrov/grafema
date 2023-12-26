<?php
/**
 * Query builder
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/builder.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="builder__fields">
	<div class="dg g-4 ga-4 gtc-4">
		<div class="dg g-1">
			<label class="dg g-1">
				<span class="df aic jcsb fw-600">Type</span>
				<select id="name">
					<optgroup label="Post">
						<option value="post_type">Post Type</option>
						<option value="post_template">Post Template</option>
						<option value="post_status">Post Status</option>
						<option value="post_format">Post Format</option>
						<option value="post_category">Post Category</option>
						<option value="post_taxonomy">Post Taxonomy</option>
						<option value="post">Post</option>
					</optgroup>
				</select>
			</label>
		</div>
		<div class="dg g-1">
			<label class="dg g-1">
				<span class="df aic jcsb fw-600">Label</span>
				<span class="field ff-code">
					<input name="permalinks[post]" placeholder="e.g. Just Another Grafema Website" required>
				</span>
			</label>
		</div>
		<div class="dg g-1">
			<label class="dg g-1">
				<span class="df aic jcsb fw-600">Name</span>
				<span class="field ff-code">
					<input name="permalinks[post]" placeholder="e.g. Just Another Grafema Website" required>
					<i class="ph ph-info" x-tooltip.click.prevent="'Single word, no spaces. Underscores and dashes allowed'"></i>
				</span>
			</label>
		</div>
		<div class="dg g-1">
			<label class="dg g-1">
				<span class="df aic jcsb fw-600">Default value</span>
				<span class="field ff-code">
					<input name="permalinks[post]" placeholder="e.g. Just Another Grafema Website" required>
				</span>
			</label>
		</div>
	</div>
</div>

<template x-for="(group, key) in groups">
	<div class="builder__group">
		<template x-for="(rule, index) in group.rules">
			<div class="builder__rules">
				<div class="dg g-1">
					<select id="name" x-bind:name="'group.rules['+ index +'][location]'" x-model="rule.location">
						<optgroup label="Post">
							<option value="post_type">Post Type</option>
							<option value="post_template">Post Template</option>
							<option value="post_status">Post Status</option>
							<option value="post_format">Post Format</option>
							<option value="post_category">Post Category</option>
							<option value="post_taxonomy">Post Taxonomy</option>
							<option value="post">Post</option>
						</optgroup>
					</select>
				</div>
				<div class="dg g-1">
					<select id="operator" x-bind:name="'group.rules['+ index +'][operator]'" x-model="rule.operator">
						<option value="===">is equal to</option>
						<option value="!=">is not equal to</option>
					</select>
				</div>
				<div class="dg g-1">
					<select id="value" x-bind:name="'group.rules['+ index +'][value]'" x-model="rule.value">
						<option value="subscriber">Subscriber</option>
						<option value="contributor">Contributor</option>
						<option value="author">Author</option>
						<option value="editor">Editor</option>
						<option value="administrator">Administrator</option>
					</select>
				</div>
				<div class="dg g-1" x-show="group.rules.length > 1">
					<button type="button" class="btn btn--icon btn--outline" @click="removeRule(key,index)">
						<i class="ph ph-trash"></i>
					</button>
				</div>
			</div>
		</template>
		<div class="builder__buttons">
			<button type="button" class="btn btn--outline" @click="removeGroup(key)" x-show="groups.length > 1">
				<i class="ph ph-trash"></i> Remove group
			</button>
			<button type="button" class="btn btn--outline ml-auto" @click="addRule(key)">
				<i class="ph ph-plus-circle"></i> And
			</button>
		</div>
	</div>
</template>
<div class="builder__buttons">
	<button class="btn btn--outline" type="button" @click="addGroup">
		<i class="ph ph-folder-simple-plus"></i> Add group
	</button>
	<button class="btn btn--primary" type="submit">
		<i class="ph ph-floppy-disk"></i> Save changes
	</button>
</div>
