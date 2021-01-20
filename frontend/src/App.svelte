<script>
import { Router, Route } from 'svelte-routing'

import Vendors from './components/Vendors.svelte'
import VendorDetails from './components/VendorDetails.svelte'

export let url = ''
</script>

<svelte:head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</svelte:head>

<Router url="{url}">
	<nav class="navbar navbar-light bg-light">
		<a class="navbar-brand" href="/">ProVend</a>
		<ul class="navbar-nav">
			<li class="nav-item active">
				<a class="nav-link" href="/vendors">Vendors</a>
			</li>
		</ul>
	</nav>
	<br/><br/>
	<div class="container">
		<Route path="vendors/:id" component="{VendorDetails}" />
		<Route path="vendors"><Vendors /></Route>
		<Route path="/"><Vendors /></Route>
	</div>
	<br/><br/>
</Router>

<style>
:global(body) {
	padding: 0px !important;
}
</style>