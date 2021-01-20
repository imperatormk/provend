<script>
import { onMount } from 'svelte'
import api from '../services/api'

let vendor = null
onMount(async () => {
	vendor = await api.getOneVendor(id)
})

let purchaseStates = ['OPEN', 'CLOSED', 'DISPUTE']
let selectedPurchase = null

let newPurchase = {
	amt: 0.01,
	release_date: new Date().toDateInputValue(),
	state: purchaseStates[0]
}
let newPurchaseDetail = {
	line_item: '',
	material_id: '',
	units: 1,
	quantity: 1,
	balance: 0,
	promised_del_date: new Date().toDateInputValue(),
	unit_cost: 1,
	status: purchaseStates[0]
}

export let id = null

function postPurchase() {
	api.postPurchase(newPurchase, id)
		.then(([postedPurchase]) => {
			postedPurchase.details = []
			vendor.purchases = [...vendor.purchases, postedPurchase]
			selectedPurchase = postedPurchase
		})
}

function postPurchaseDetail() {
	api.postPurchaseDetail(newPurchaseDetail, selectedPurchase.id)
		.then(([postedPurchaseDetail]) => {
			selectedPurchase.details = [...selectedPurchase.details, postedPurchaseDetail]
		})
}

function deletePurchase(id) {
	api.deletePurchase(id)
		.then(() => {
			vendor.purchases = vendor.purchases.filter(item => item.id !== id)
		})
}
</script>

<h5><a href="/vendors" class="pr-3">Back</a></h5>
<h2>Vendor details</h2>
<br>
<div class="row">
	<div class="col-4">
		{#if vendor}
			<div class="card">
				<div class="card-body">
					<h2>{vendor.name}</h2>
					<p>{vendor.street}, {vendor.state}, {vendor.city}, {vendor.zip}</p>
					{#if vendor.purchases.length}
						<h4>Purchases ({vendor.purchases.length})</h4>
						<br> 
						<table class="table table-sm text-center">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Release date</th>
									<th scope="col">Amount</th>
									<th scope="col">State</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
								{#each vendor.purchases as purchase}
									<tr>
										<td><a class="font-weight-bold" on:click="{() => { selectedPurchase = purchase }}" href="{'#'}">{purchase.id}</a></td>
										<td>{purchase.release_date}</td>
										<td>{purchase.amt}</td>
										<td>{purchase.state}</td>
										<td>
											<button on:click="{deletePurchase(purchase.id)}" class="btn btn-danger">D</button>
										</td>
									</tr>
								{/each}
							</tbody>
						</table>
						<span class="pb-1"></span>
					{/if}
					<form on:submit|preventDefault="{postPurchase}">
						<h4>New purchase</h4>
						<br> 
						<div class="form-group"><input bind:value="{newPurchase.amt}" class="form-control" type="number" min="0.01" step="0.01" required placeholder="Amount"></div>
						<div class="form-group"><input bind:value="{newPurchase.release_date}" class="form-control" type="date" required placeholder="Release date"></div>
						<div class="form-group">
							<select bind:value="{newPurchase.state}" class="form-control" required>
								{#each purchaseStates as purchaseState}
									<option>{purchaseState}</option>
								{/each}
							</select>
						</div>
						<button type="submit" class="btn btn-success">Submit</button>
					</form>
				</div>
			</div>
		{/if}
	</div>
	<div class="col-8">
		{#if selectedPurchase}
			<div class="card">
				<div class="card-body">
					<h4>Purchase details #{selectedPurchase.id}</h4>
					<br/>
					<table class="table table-sm text-center">
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
									<td>{detail.id}</td>
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
					<span class="pb-1"></span>
					<form on:submit|preventDefault="{postPurchaseDetail}">
						<h4>New purchase detail</h4>
						<br/>
						<div class="form-group"><input bind:value="{newPurchaseDetail.line_item}" class="form-control" required placeholder="Line item"></div>
						<div class="form-group"><input bind:value="{newPurchaseDetail.material_id}" class="form-control" required placeholder="Material ID"></div>
						<div class="form-group"><input bind:value="{newPurchaseDetail.units}" class="form-control" type="number" min="1" step="1" required placeholder="Units"></div>
						<div class="form-group"><input bind:value="{newPurchaseDetail.quantity}" class="form-control" type="number" min="1" step="1" required placeholder="Quantity"></div>
						<div class="form-group"><input bind:value="{newPurchaseDetail.balance}" class="form-control" type="number" step="0.1" required placeholder="Balance"></div>
						<div class="form-group"><input bind:value="{newPurchaseDetail.promised_del_date}" class="form-control" type="date" required placeholder="Promised deletion date"></div>
						<div class="form-group"><input bind:value="{newPurchaseDetail.unit_cost}" class="form-control" type="number" min="0.1" step="0.1" required placeholder="Unit cost"></div>
						<div class="form-group">
							<select bind:value="{newPurchaseDetail.status}" class="form-control" required>
								{#each purchaseStates as purchaseState}
									<option>{purchaseState}</option>
								{/each}
							</select>
						</div>
						<div class="d-flex">
							<button type="submit" class="btn btn-success">Submit</button>
							<span class="px-1"></span>
							<button type="button" on:click="{() => selectedPurchase = null}" class="btn btn-danger">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		{/if}
	</div>
</div>