<script>
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
					<p>{vendor.street}, {vendor.state}, {vendor.city}, {vendor.zip}</p>
					<a href="/vendors/{vendor.id}">Details</a>
				</div>
			</div>
		{/each}
	{/await}
</div>