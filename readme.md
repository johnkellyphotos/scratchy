Here is a **README.md** in Markdown tailored to the purpose indicated by the repository (“Create HTML in PHP”) and typical structure (src + example):

# Scratchy

A minimal PHP utility for generating HTML programmatically.

## Overview

Scratchy provides simple helpers to build HTML markup from PHP. It is designed for ease of use and to keep templates clean by separating HTML generation logic.

## Features

- Programmatic construction of HTML
- Lightweight, no external dependencies

## Installation

Clone the repository:

```bash
git clone https://github.com/johnkellyphotos/scratchy.git
````

## Usage

By default, requests to the root domain are routed to the Home controller and invoke its index action (HomeController::index()). This default routing is configurable in defaults.php. When no controller or action is specified, the request is automatically dispatched to this controller method.

Other requests follow the pattern:

https://[your-domain]/[controller]/[action]. For example, a request "https://www.yourdomain.com/Buy-Stuff/a-new-car/" would call the `BuyStuffController::aNewCar()`.