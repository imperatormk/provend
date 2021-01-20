<script>
import { onMount } from 'svelte'
import api from '../services/api'
import templates from '../templates'

let vendors = []
onMount(async () => {
	vendors = await api.getVendors()
})

let newVendor = { ...templates.vendor }

function postVendor() {
	api.postVendor(newVendor)
		.then((postedVendor) => {
			vendors = [...vendors, postedVendor]
			newVendor = { ...templates.vendor }
		})
}

function deleteVendor(id) {
	api.deleteVendor(id)
		.then(() => {
			vendors = vendors.filter(item => item.id !== id)
		})
}
</script>

<h2>Vendors</h2>
<br/>

<div class="row">
	{#each vendors as vendor}
		<div class="col-4">
			<div class="card">
				<div class="card-body d-flex flex-column">
					<h5>{vendor.name}</h5>
					<p>{vendor.street}, {vendor.state}, {vendor.city}, {vendor.zip}</p>
					<span style="flex-grow: 1;"></span>
					<div class="d-flex justify-content-between">
						<a href="/vendors/{vendor.id}">Details</a>
						<a on:click="{() => deleteVendor(vendor.id)}" class="text-danger" href="{'#'}">Delete</a>
					</div>
				</div>
			</div>
		</div>
	{/each}
</div>

<div class="row">
	<div class="col-6">
		<form on:submit|preventDefault="{postVendor}">
			<br/>
			<h4>New vendor</h4>
			<br/>
			<div class="form-group"><input bind:value="{newVendor.name}" class="form-control" required placeholder="Name"></div>
			<div class="form-group"><input bind:value="{newVendor.street}" class="form-control" required placeholder="Street"></div>
			<div class="form-group"><input bind:value="{newVendor.state}" class="form-control" required placeholder="State"></div>
			<div class="form-group"><input bind:value="{newVendor.city}" class="form-control" required placeholder="City"></div>
			<div class="form-group"><input bind:value="{newVendor.zip}" class="form-control" required placeholder="Zip"></div>
			<button type="submit" class="btn btn-success">Submit</button>
		</form>
	</div>
</div>