const purchaseStates = ['OPEN', 'CLOSED', 'DISPUTE']

Date.prototype.toDateInputValue = (function() {
	return new Date(this).toJSON().slice(0, 10)
})

export default {
	vendor: {
		name: '',
		street: '',
		state: '',
		city: '',
		zip: ''
	},
	purchaseStates,
	purchase: {
		amt: 0.01,
		release_date: new Date().toDateInputValue(),
		state: purchaseStates[0]
	},
	purchaseDetail: {
		line_item: 0,
		material_id: 0,
		units: '',
		quantity: 1,
		balance: 0,
		promised_del_date: new Date().toDateInputValue(),
		unit_cost: 1,
		status: purchaseStates[0]
	}
}