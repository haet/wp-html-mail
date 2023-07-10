import React from "react";
import WoocommerceSalesPage from "./WoocommerceSalesPage";

class RemoteWoocommerceComponentErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, fallbackComponent: false };
  }

  static getDerivedStateFromError(error) {    // Update state so the next render will show the fallback UI.    
    let fallbackComponent = <></>
    if (error.toString().indexOf("wphtmlmailwoocommerce"))
      fallbackComponent = <WoocommerceSalesPage />

    return { hasError: true, fallbackComponent: fallbackComponent };
  }

  componentDidCatch(error, errorInfo) {    // You can also log the error to an error reporting service    
    console.log(error, errorInfo);
    if (error.toString().indexOf("Woocommerce"))
      console.log('#### Woocommerce MODULE NOT FOUND');
  }

  render() {
    if (this.state.hasError && this.state.fallbackComponent) {      // You can render any custom fallback UI      
      return this.state.fallbackComponent;
    }
    return this.props.children;
  }
}

export default RemoteWoocommerceComponentErrorBoundary;