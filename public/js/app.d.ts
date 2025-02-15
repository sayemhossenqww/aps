export = webpackContext;
declare function webpackContext(req: any): any;
declare namespace webpackContext {
    export { keys, webpackContextResolve as resolve, id, __esModule };
}
declare function keys(): string[];
declare function webpackContextResolve(req: any): any;
declare var id: string;
declare const __esModule: boolean;
