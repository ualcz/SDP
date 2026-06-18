import { chromium } from "playwright";

async function main() {

    const browser = await chromium.launch({

        headless: false

    });

    const page = await browser.newPage();

    await page.goto("https://suap.ifba.edu.br/accounts/login/");

    console.log("Página aberta!");

    await page.waitForTimeout(5000);

    await browser.close();

}

main();