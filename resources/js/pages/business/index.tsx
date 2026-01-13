import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head, router } from "@inertiajs/react";
import { create, index, show } from "@/routes/business";
import { BusinessDataTable } from "@/components/business/data-table";
import { columns } from "@/components/business/columns";
import { Button } from "@/components/ui/button";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Business',
        href: index().url,
    },
];

export default function BusinessIndex({ businesses }: { businesses: any }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}  >
            <Head title="Business" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 w-1/2 mx-auto">

            <div className="flex gap-4 items-center justify-between">
            <h1 className="text-2xl font-bold">Businesses</h1>
                
                <Button
                    type="submit"
                    className="mt-4 w-2/5 whitespace-normal h-auto"
                    onClick={() => router.visit(create())}
                    >
                    Create New Business
                </Button>
            </div>
           
                    <BusinessDataTable columns={columns} data={businesses} />
            </div>
        </AppLayout>
    );
}