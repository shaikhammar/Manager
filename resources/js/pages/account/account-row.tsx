import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { ChevronDown, ChevronRight } from "lucide-react";
import { TableCell, TableRow } from "@/components/ui/table";
import { Lock } from "lucide-react";
import { useState } from "react";
import { Account } from "@/types";

const AccountRow = ({ account, depth = 0 }: { account: Account; depth?: number }) => {
    const [isOpen, setIsOpen] = useState<boolean>(true);
    const hasChildren : boolean = account.children?.length > 0;

    return (
        <>
            <TableRow className={depth === 0 ? "bg-muted/50 font-medium" : ""}>
                <TableCell className="font-mono text-xs text-muted-foreground">
                    <span style={{ paddingLeft: `${depth * 1.5}rem` }}>
                        {account.code}
                    </span>
                </TableCell>
                <TableCell>
                    <div className="flex items-center" style={{ paddingLeft: `${depth * 1.5}rem` }}>
                        {hasChildren ? (
                            <button onClick={() => setIsOpen(!isOpen)} className="mr-2">
                                {isOpen ? <ChevronDown className="h-4 w-4" /> : <ChevronRight className="h-4 w-4" />}
                            </button>
                        ) : (
                            <div className="w-6" /> 
                        )}
                        <span className={!account.is_selectable ? "text-muted-foreground italic" : "font-medium"}>
                            {account.name}
                        </span>
                        {account.is_system && (
                            <Badge variant="secondary" className="ml-2 gap-1 px-1.5 py-0">
                                <Lock className="h-3 w-3" /> System
                            </Badge>
                        )}
                    </div>
                </TableCell>
                <TableCell>
                    <Badge variant="outline">{account.type}</Badge>
                </TableCell>
                <TableCell className="text-right font-mono">
                    {/* Placeholder for balance logic */}
                    $0.00
                </TableCell>
                <TableCell className="text-right">
                    <Button variant="ghost" size="sm">Edit</Button>
                </TableCell>
            </TableRow>
            {isOpen && hasChildren && account.children.map((child: any) => (
                <AccountRow key={child.id} account={child} depth={depth + 1} />
            ))}
        </>
    );
};

export default AccountRow;
