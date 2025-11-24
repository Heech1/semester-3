DATA_PATH = "online_retail.csv"

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from mlxtend.preprocessing import TransactionEncoder
from mlxtend.frequent_patterns import apriori, association_rules

def load_and_clean(path, country_filter=None):
    df = pd.read_csv(path, encoding='latin1')

    # Basic cleaning
    df = df.dropna(subset=['InvoiceNo', 'Description'])
    df = df[~df['InvoiceNo'].astype(str).str.startswith('C')]
    df = df[df['Quantity'] > 0]
    df = df[df['UnitPrice'] > 0]

    # Optional country filter
    if country_filter is not None:
        df = df[df['Country'] == country_filter]

    return df

def filter_top_items(df, top_n=100):
    top_items = df['Description'].value_counts().head(top_n).index
    df = df[df['Description'].isin(top_items)]
    return df

def create_transactions(df):
    basket = df.groupby('InvoiceNo')['Description'].apply(lambda x: list(set(x)))
    return basket.tolist()

def run_apriori(transactions, min_support=0.01, min_confidence=0.3, min_lift=1.0):
    print("Encoding transactions...")
    te = TransactionEncoder()
    te_ary = te.fit(transactions).transform(transactions)
    trans_df = pd.DataFrame(te_ary, columns=te.columns_)

    print("Running apriori...")
    freq = apriori(trans_df, min_support=min_support, use_colnames=True)

    print("Generating rules...")
    rules = association_rules(freq, metric="confidence", min_threshold=min_confidence)
    rules = rules[rules['lift'] >= min_lift]

    rules = rules.sort_values(
        by=['lift', 'confidence', 'support'],
        ascending=[False, False, False]
    )
    return freq, rules, trans_df

def save_rules(rules, out_csv="rules.csv", top_n=50):
    df = rules.copy()
    df['antecedents'] = df['antecedents'].apply(lambda x: ', '.join(list(x)))
    df['consequents'] = df['consequents'].apply(lambda x: ', '.join(list(x)))

    df[['antecedents','consequents','support','confidence','lift']].to_csv(
        out_csv, index=False
    )
    print(f"Saved rules to {out_csv}")
    return df.head(top_n)

def plot_top_items(freq, top_n=20, out_png="top_itemsets.png"):
    singles = freq[freq['itemsets'].apply(lambda x: len(x)==1)].copy()
    singles['item'] = singles['itemsets'].apply(lambda x: list(x)[0])
    singles = singles.sort_values('support', ascending=False).head(top_n)

    plt.figure(figsize=(10,6))
    plt.barh(range(len(singles)), singles['support'][::-1])
    plt.yticks(range(len(singles)), singles['item'][::-1])
    plt.title("Top Frequent Items")
    plt.tight_layout()
    plt.savefig(out_png)
    plt.close()
    print(f"Saved plot to {out_png}")

def main():
    print("Loading dataset...")
    df = load_and_clean(DATA_PATH, country_filter="United Kingdom")
    print("Rows after cleaning:", len(df))

    print("Filtering top frequent items...")
    df = filter_top_items(df, top_n=100)
    print("Rows after item filtering:", len(df))
    print("Unique items:", df['Description'].nunique())

    print("Creating transactions...")
    transactions = create_transactions(df)
    print("Total transactions:", len(transactions))

    print("Running Apriori (super fast mode)...")
    freq, rules, trans_df = run_apriori(
        transactions,
        min_support=0.01,
        min_confidence=0.3,
        min_lift=1.0
    )

    print("Frequent itemsets:", len(freq))
    print("Rules generated:", len(rules))

    if len(rules) == 0:
        print("No rules found. Lower min_support.")
    else:
        top_rules = save_rules(rules)
        print("\nTop 10 rules:")
        print(top_rules.head(10).to_string(index=False))

    plot_top_items(freq)

if __name__ == "__main__":
    main()
