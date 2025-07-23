Feature: Prove the product list work as expected in the requirements

    Background:
        Given I load the large data fixtures

    Scenario: Load main page
        Given I call '/products'
        Then  I should have the response content:
            """
            {
                "count": 5,
                "page": 1,
                "products": [
                    {
                        "sku": "000001",
                        "name": "BV Lean leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 89000,
                            "final": 62300,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    },
                    {
                        "sku": "000002",
                        "name": "BV Lean leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 99000,
                            "final": 69300,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    },
                    {
                        "sku": "000003",
                        "name": "Ashlington leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 71000,
                            "final": 49700,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    },
                    {
                        "sku": "000004",
                        "name": "Naima embellished suede sandals",
                        "category": "sandals",
                        "price": {
                            "original": 79500,
                            "final": 79500,
                            "discount_percentage": null,
                            "currency": "EUR"
                        }
                    },
                    {
                        "sku": "000005",
                        "name": "Nathane leather sneakers",
                        "category": "sneakers",
                        "price": {
                            "original": 59000,
                            "final": 59000,
                            "discount_percentage": null,
                            "currency": "EUR"
                        }
                    }
                ]
            }
            """
        And the response status code is 200

    Scenario: Load category products
        Given I call '/products?category=boots'
        Then  I should have the response content:
            """
            {
                "count": 3,
                "page": 1,
                "products": [
                    {
                        "sku": "000001",
                        "name": "BV Lean leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 89000,
                            "final": 62300,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    },
                    {
                        "sku": "000002",
                        "name": "BV Lean leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 99000,
                            "final": 69300,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    },
                    {
                        "sku": "000003",
                        "name": "Ashlington leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 71000,
                            "final": 49700,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    }
                ]
            }
            """
        And the response status code is 200

    Scenario: Load products less than a Price
        Given I call '/products?priceLessThan=70000'
        Then  I should have the response content:
            """
            {
                "count": 1,
                "page": 1,
                "products": [
                    {
                        "sku": "000005",
                        "name": "Nathane leather sneakers",
                        "category": "sneakers",
                        "price": {
                            "original": 59000,
                            "final": 59000,
                            "discount_percentage": null,
                            "currency": "EUR"
                        }
                    }
                ]
            }
            """
        And the response status code is 200

    Scenario: Load products from category less than a Price
        Given I call '/products?priceLessThan=80000&category=boots'
        Then  I should have the response content:
            """
            {
                "count": 1,
                "page": 1,
                "products": [
                    {
                        "sku": "000003",
                        "name": "Ashlington leather ankle boots",
                        "category": "boots",
                        "price": {
                            "original": 71000,
                            "final": 49700,
                            "discount_percentage": "30.00%",
                            "currency": "EUR"
                        }
                    }
                ]
            }
            """
        And the response status code is 200


    Scenario: Load products invalid scenarios
        Given I call '/products?priceLessThan=0'
        Then the response status code is 404
        Given I call '/products?priceLessThan=ten'
        Then the response status code is 404
        Given I call '/products?page=-1'
        Then the response status code is 404
        Given I call '/products?page=one'
        Then the response status code is 404
