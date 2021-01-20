import http from './http'

export default {
  getVendors() {
    return http.get('/vendors/')
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
	getOneVendor(id) {
    return http.get(`/vendors/${id}`)
			.then(resp => resp.data)
			.then(async (vendor) => {
				const purchasesWithDetails = await Promise.all(vendor.purchases.map(purchase => this.getPurchaseDetails(purchase.id)))
				return {
					...vendor,
					purchases: purchasesWithDetails
				}
			})
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
	getPurchaseDetails(id) {
		return http.get(`/purchases/${id}`)
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
	postPurchase(purchase, vendorId) {
		const reqObj = {
			...purchase,
			vendor_id: +vendorId
		}
		return http.post('/purchases/', reqObj)
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
  },
  postPurchaseDetail(purchaseDetail, purchaseId) {
		const reqObj = {
			...purchaseDetail,
			purchase_id: +purchaseId
		}
		return http.post('/purchase-details/', reqObj)
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
  },
  deletePurchase(id) {
		return http.delete(`/purchases/${id}`)
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
}