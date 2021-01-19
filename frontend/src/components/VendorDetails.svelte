<script>
import api from '../services/api'

let selectedPurchase = null
let newPurchase = {
	amt: 0.01,
	release_date: new Date(),
	state: 'OPEN'
}
export let id = null

function postPurchase() {
	api.postPurchase(newPurchase, id)
		.then((postedPurchase) => {
			console.log(postedPurchase)
		})
}
</script>

<h2>Vendor details</h2>
<br>
<div class="row">
	{#await api.getOneVendor(id) then vendor}
	<div class="col-4">
		<div class="card">
			<div class="card-body">
				<h2>{vendor.name}</h2>
				<p>{vendor.street}, {vendor.state}, {vendor.city}, {vendor.zip}</p>
				<h4>Purchases ({vendor.purchases.length})</h4>
				<br> 
				<table class="table table-sm">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Release date</th>
							<th scope="col">Amount</th>
							<th scope="col">State</th>
						</tr>
					</thead>
					<tbody>
						{#each vendor.purchases as purchase}
							<tr>
								<td><a on:click="{() => { selectedPurchase = purchase }}" href="{'#'}">{purchase.id}</a></td>
								<td>{purchase.release_date}</td>
								<td>{purchase.amt}</td>
								<td>{purchase.state}</td>
							</tr>
						{/each}
					</tbody>
				</table>
				<br> 
				<form on:submit|preventDefault="{postPurchase}">
					<h4>New purchase</h4>
					<br> 
					<div class="form-group"><input bind:value="{newPurchase.amt}" class="form-control" type="number" min="0.01" step="0.01" required placeholder="Amount"></div>
					<div class="form-group"><input bind:value="{newPurchase.release_date}" class="form-control" type="date" required placeholder="Release date"></div>
					<div class="form-group"><input bind:value="{newPurchase.state}" class="form-control" required placeholder="State"></div>
					<button type="submit" class="btn btn-success">Submit</button>
				</form>
			</div>
		</div>
	</div>
	{/await}
	<div class="col-8">
	{#if selectedPurchase}
		<div class="card">
			<div class="card-body">
				<h4>Purchase details</h4>
				<br/>
				<table class="table table-sm">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Line item</th>
							<th scope="col">Material ID</th>
							<th scope="col">Units</th>
							<th scope="col">Quantity</th>
							<th scope="col">Balance</th>
							<th scope="col">Promised deletion date</th>
							<th scope="col">Unit cost</th>
							<th scope="col">State</th>
						</tr>
					</thead>
					<tbody>
						{#each selectedPurchase.details as detail}
							<tr>
								<td><a href="{'#'}">{detail.id}</a></td>
								<td>{detail.line_item}</td>
								<td>{detail.material_id}</td>
								<td>{detail.units}</td>
								<td>{detail.quantity}</td>
								<td>{detail.balance}</td>
								<td>{detail.promised_del_date}</td>
								<td>{detail.unit_cost}</td>
								<td>{detail.status}</td>
							</tr>
						{/each}
					</tbody>
				</table>
			</div>
		</div>
	{/if}
	</div>
</div>