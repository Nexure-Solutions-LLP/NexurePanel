import requests
from langchain_core.tools import tool

BASE_URL = "https://us-east-1.nexure-cloud-compute-130-12-30-4.nexuresolutions.com/Modules/Stripe/Payments/Backend/index.php"

@tool
def process_payment(
    customer_id: str,
    amount: float,
    payment_method_id: str,
    currency: str = "usd"
) -> str:
    """Charge a customer via the Stripe PHP backend."""
    response = requests.post(BASE_URL, data={
        "action": "processPayment",
        "customer_id": customer_id,
        "amount": int(amount * 100),
        "payment_method_id": payment_method_id,
        "currency": currency
    })
    return response.text

@tool
def get_credit_balance(customer_id: str) -> str:
    """Retrieve a customer's credit balance."""
    response = requests.post(BASE_URL, data={
        "action": "getCreditBalance",
        "customer_id": customer_id
    })
    return response.text

@tool
def create_subscription(customer_id: str, price_id: str) -> str:
    """Create a subscription for a customer."""
    response = requests.post(BASE_URL, data={
        "action": "createSubscription",
        "customer_id": customer_id,
        "price_id": price_id
    })
    return response.text

@tool
def add_customer(
    name: str,
    email: str,
    phone: str,
    account_number: str
) -> str:
    """Add a new Stripe customer."""
    response = requests.post(BASE_URL, data={
        "action": "addCustomer",
        "name": name,
        "email": email,
        "phone": phone,
        "account_number": account_number
    })
    return response.text

TOOLS = [
    process_payment,
    get_credit_balance,
    create_subscription,
    add_customer
]
