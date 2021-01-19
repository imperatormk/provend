<script>
import { Link } from 'svelte-routing' 
import api from '../services/api'
</script>

<h2>Vendors</h2>
<br/>

<div class="row">
	{#await api.getVendors() then vendors}
		{#each vendors as vendor}
			<div class="card col-4 mx-1">
				<div class="card-body">
					<h5>{vendor.name}</h5>
					<p>{vendor.city}, {vendor.state}</p>
					<Link to="/vendors/{vendor.id}">Details</Link>
				</div>
			</div>
		{/each}
	{/await}
</div>