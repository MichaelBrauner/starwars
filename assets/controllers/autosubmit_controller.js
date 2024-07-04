import {Controller} from "@hotwired/stimulus"
import debounce from 'debounce'

export default class extends Controller {

  connect() {
    this.debouncedSubmit = debounce(this.debouncedSubmit, 300)
    this.setupClearable();
  }

  setupClearable() {
    const searchInput = this.element.querySelector('input[type="search"]')
    if (!searchInput) return;

    searchInput.addEventListener('keydown', this.clearSearchFieldsOnEscape);
  }

  submit() {
    this.element.requestSubmit()
  }

  debouncedSubmit = () => {
    this.submit()
  }

  clearSearchFieldsOnEscape = (event) => {
    if (event.key === "Escape") {
      event.currentTarget.value = ""
      this.submit()
    }
  }
}
