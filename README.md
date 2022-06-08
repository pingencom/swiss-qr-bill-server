## Introduction

An HTTP Server to generate a Swiss QR-Bill and optionally attach it to an existing PDF.

## Demo & Example

A demo instance of this server is running for your convenience and for testing purposes.

http://swiss-qr-bill-server-demo.pingen.com/

**Do not use this server in production!**

To make an API call, copy this to your console and generate your first Swiss QR-Bill.

```
curl -o file.pdf -X POST http://swiss-qr-bill-server-demo.pingen.com/api/generate \
-H "Content-Type: multipart/form-data" \
-F "creditor_name=ACME Creditor" \
-F "creditor_street=Long street" \
-F "creditor_street_number=4" \
-F "creditor_post_code=8051" \
-F "creditor_city=Zürich" \
-F "creditor_country=CH" \
-F "debitor_name=ACME Debitor" \
-F "debitor_street=Short street" \
-F "debitor_street_number=2a" \
-F "debitor_post_code=3000" \
-F "debitor_city=Bern" \
-F "debitor_country=CH" \
-F "iban=CH4431999123000889012" \
-F "total_amount=1240.25" \
-F "currency=CHF" \
-F "reference=12345" \
-F "additional_information=Additional information to this invoice" \
-F "type=QRR" \
-F "language=de"
```

If you would like to add the swiss qr-bill to an existing pdf, you can do this by adding following form parameters:

```
-F file=@path_to_file.pdf \
-F "file_mode=add"
```

... or overlay the QR-Bill over an existing page, use these form fields:

```
-F file=@path_to_file.pdf \
-F "file_mode=overlay" \
-F "file_overlay_page=1"
```

## Requirements

Docker is required to run this http server. (For example how to install on ubuntu: https://docs.docker.com/engine/install/ubuntu/)

## Run the server in docker

```
docker run -p 80:8080 pingengmbh/swiss-qr-bill-server:latest
```

## Usage

Make a HTTP Form-Post (multipart/form-data) to ..../api/generate passing the correct form values and optionally the existing pdf to be merged (see example above)


## Parameters

|       Parameter        |     Values     |                            Description                             |
|:----------------------:|:--------------:|:------------------------------------------------------------------:|
|       creditor_*       |     a few      |     All necessary information about the receiver of the money      |
|       debitor_*        |     a few      |            All information about the payer of the bill             |
|          iban          |     CH...      |                   Swiss Bank account IBAN number                   |
|      total_amount      |     float      |          The amount of the bill, maximum 2 decimal digits          |
|        currency        |    CHF, EUR    |                                                                    |
|       reference        |  1-26 digits   | The 27th digit of the reference number is automatically calculated |
| additional_information |   free text    |                                                                    |
|          type          |    QRR, NON    |    QRR is the option with reference number, NON the one without    |
|        language        | de, en, fr, it |                                                                    |
|          file          |     Binary     |      The existing PDF file you want to enhance with a qr-bill      |
|       file_mode        |  add, overlay  |                   How you want to merge the PDFs                   |
|   file_overlay_page    |      1-99      | In case of overlay mode, on which page should the overlay be done  |

# Bugreport & Contribution

If you find a bug, please either create a ticket in github, or initiate a pull request.