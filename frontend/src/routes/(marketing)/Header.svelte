<script lang="ts">
	import logo from '$lib/images/logo.svg';
	import Button from '$lib/components/Button.svelte';
	import { page } from "$app/stores";
</script>

<header>

	<nav class="container">

		<div class="nav-left">
            <a class="nav-brand" href="/">
                <img src={logo} alt="Hyvor Shield Logo" width="30" height="30" />
                <span class="brand-name">Hyvor Reader</span>
            </a>
        </div>

		<div class="nav-right">

			<a href="/find">
				<Button active={$page.url.pathname === '/find'}>
					Find Feed
				</Button>
			</a>

			<a href="/learn" class="learn-button">
				<Button active={$page.url.pathname.startsWith('/learn')}>
					Learn RSS
				</Button>
			</a>

			<a href="/app">
				<Button>
					Log in
				</Button>
			</a>
			<a href="/app?signup">
				<Button accent>
					Sign up for FREE
				</Button>
			</a>
		</div>

	</nav>

</header>

<style>
	header {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 100;
		background-color: var(--accent-lightest);
		border-bottom: 1px solid var(--border);
		height: var(--header-height);
		display: flex;
		align-items: center;
	}
	nav {
		display: flex;
        align-items: center;
	}
	.nav-brand {
		display: inline-block;
		font-size: 1.25rem;
		line-height: inherit;
		white-space: nowrap;
		color: inherit;
		text-decoration: none;
		font-weight: 600;
	}
	.nav-brand img {
		vertical-align: middle;
	}
	.brand-name {
		vertical-align: middle;
	}

	.nav-left {
		flex: 1;
	}

	.learn-button {
		margin-right: 10px;
	}
</style>
