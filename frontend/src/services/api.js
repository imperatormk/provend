import http from './http'

export default {
  getVendors() {
    return http.get('/vendors.php')
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
	getOneVendor(id) {
    return http.get('/vendors.php', { params: { id, subentity: 'purchase' } })
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
  postVendor(vendor) {
    const reqObj = { ...vendor }
    return http.post('/vendors.php', reqObj)
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
  },
  deleteVendor(id) {
		return http.delete('/vendors.php?id=${id}', { params: { id } })
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
	getPurchaseDetails(id) {
		return http.get(`/purchases.php`, { params: { id, subentity: 'detail' } })
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
		return http.post('/purchases.php', reqObj)
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
		return http.post('/details.php', reqObj)
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
  },
  deletePurchase(id) {
    return http.delete('/purchases.php?id=${id}', { params: { id } })
      .then(resp => resp.data)
      .catch((err) => {
        const { data } = err.response
        return Promise.reject(data)
      })
	},
}