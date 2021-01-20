import App from './App.svelte'

Date.prototype.toDateInputValue = (function() {
	return new Date(this).toJSON().slice(0,10)
})

const app = new App({
	target: document.body
})

export default app