<script>
	import Footer from './Footer.svelte';
	import '../styles.css';
	import { Header } from '@hyvor/design/marketing';

	import logo from '$lib/images/logo.svg';
	import { Button, DarkToggle, IconButton } from '@hyvor/design/components';
	import { page } from '$app/stores';
	import { IconGithub } from '@hyvor/icons';
</script>

<div class="app">
	<Header {logo} name="HYVOR" subName="Reader" darkToggle={false}>
		<div slot="center">
			<a href="/find">
				<Button active={$page.url.pathname === '/find'} color="invisible">Find Feed</Button>
			</a>

			<a href="/learn" class="learn-button">
				<Button active={$page.url.pathname.startsWith('/learn')} color="invisible">
					Learn RSS
				</Button>
			</a>
		</div>

		<div slot="end" class="header-end">
			<span class="icons">
				<DarkToggle />

				<IconButton
					color="invisible"
					as="a"
					href="https://github.com/hyvor/reader.hyvor.com"
					target="_blank"
				>
					<IconGithub />
				</IconButton>
			</span>

			<a href="/app?signup">
				<Button color="accent">Go to App</Button>
			</a>
		</div>
	</Header>

	<main>
		<slot />
	</main>

	<Footer />
</div>

<style>
	.header-end {
		display: flex;
		align-items: center;
	}
	.header-end .icons {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		margin-right: 10px;
	}
</style>
