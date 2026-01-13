import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem, Country, Currency } from "@/types";
import { Form, Head, router } from "@inertiajs/react";
import { index } from "@/routes/business";
import {store} from "@/actions/App/Http/Controllers/BusinessController";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import  InputError  from "@/components/input-error";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { usePage } from "@inertiajs/react";
import { toast } from "sonner";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Business',
        href: index().url,
    },
];

export default function BusinessCreate() {
    const { countries, currencies } = usePage<{ countries: Country[], currencies: Currency[] }>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}  >
            <Head title="Create New Business" />
                        <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <div className="flex flex-col gap-4 w-full max-w-2xl mx-auto p-4">
            <h1 className="text-2xl font-bold">Create New Business </h1>

            <Form {...store.form()}
            options={{
                preserveScroll: true,
            }}
            resetOnSuccess
            disableWhileProcessing
            onSuccess={() => {
                toast.success('Business created successfully');
            }}
            >
                {({ errors, processing}) => (
                    <>
                        <div className="grid gap-2 w-full p-4">
                            <Label htmlFor="name">Name</Label>
                            <Input
                                id="name"
                                name="name"
                                type="text"
                                required
                                autoFocus
                                tabIndex={1}
                                placeholder="Enter business name"
                                />
                            <InputError message={errors.name} />
                        </div>
                        <div className="grid gap-2 w-full p-4">
                            <Label htmlFor="code">Code</Label>
                            <Input
                                id="code"
                                name="code"
                                type="text"
                                required
                                tabIndex={2}
                                placeholder="Enter business code"
                                />
                            <InputError message={errors.code} />
                        </div>
                        <div className="grid gap-2 w-full p-4">
                            <Label htmlFor="country_id">Country</Label>
                            <Select
                                name="country_id"
                                required
                                >
                                <SelectTrigger tabIndex={3}>
                                    <SelectValue placeholder="Select country"/>
                                </SelectTrigger>
                                <SelectContent>
                                {countries.map((country: Country) => (
                                    <SelectItem key={country.id} value={country.id.toString()}>{country.name}</SelectItem>
                                ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.country_id} />
                        </div>
                        <div className="grid gap-2 w-full p-4">
                            <Label htmlFor="currency_id">Currency</Label>
                            <Select
                                name="currency_id"
                                required
                            >
                                <SelectTrigger tabIndex={4}>
                                    <SelectValue placeholder="Select currency"/>
                                </SelectTrigger>
                                <SelectContent>
                                {currencies.map((currency: Currency) => (
                                    <SelectItem key={currency.id} value={currency.id.toString()}>{currency.name}</SelectItem>
                                ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.currency_id} />
                        </div>
                        <div className="flex flex-col md:flex-row gap-4 mt-4">
                            <Button
                                type="submit"
                                disabled={processing}
                                tabIndex={5}
                                className="w-full md:w-1/2 whitespace-normal h-auto"
                            >
                                Create New Business
                            </Button>
                            <Button
                                type="button"
                                variant="destructive"
                                disabled={processing}
                                tabIndex={6}
                                className="w-full md:w-1/2"
                                onClick={() => router.visit(index())}
                            >
                                Cancel
                            </Button>
                        </div>
                    </>
                )}
            </Form>
                </div>
            
            </div>
        </AppLayout>
    );
}